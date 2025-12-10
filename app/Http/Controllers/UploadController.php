<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\frontend\ApplicationForm\Application;
use App\Models\frontend\ApplicationForm\ApplicantDetail;
use App\Models\frontend\ApplicationForm\PropertyDetail;
use App\Models\frontend\ApplicationForm\Accommodation;
use App\Models\frontend\ApplicationForm\Facility;
use App\Models\frontend\ApplicationForm\PhotosSignature;
use App\Models\frontend\ApplicationForm\Enclosure;
use App\Models\frontend\ApplicationForm\Document;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UploadController extends Controller
{

    public function store(Application $application, Request $r)
    {
        $r->validate([
            'file'     => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:20480', // 20 MB cap (kilobytes)
            'category' => 'required|string|max:50',
        ]);

        $uploaded = $r->file('file');
        $category = $r->input('category');
        $mime = $uploaded->getMimeType();
        $isImage  = str_starts_with($mime, 'image/');

        // Handle property_photos differently - allow multiple files
        if ($category === 'property_photos') {
            // Check if this is the 6th photo
            $existingPhotosCount = $application->documents()
                ->where('category', 'property_photos')
                ->count();

            if ($existingPhotosCount >= 5) {
                throw ValidationException::withMessages([
                    'file' => 'Maximum 5 property photos allowed. Please delete existing photos to upload new ones.',
                ]);
            }
        }

        // Set directory base
        $dir  = "applications/{$application->id}/docs";

        if ($isImage) {
            // Re-encode and compress to ensure ≤ 200 KB
            // NOTE: keep your Image API consistent with the package you use.
            $image = Image::read($uploaded->getRealPath())
                ->scaleDown(2000, 2000); // safety cap for huge photos

            $attempts = [
                ['ext' => 'webp', 'mime' => 'image/webp', 'qualities' => [80, 70, 60, 50, 40, 35, 30]],
                ['ext' => 'jpg',  'mime' => 'image/jpeg', 'qualities' => [80, 70, 60, 50, 40, 35, 30]],
            ];

            $encoded = null;
            foreach ($attempts as $codec) {
                foreach ($codec['qualities'] as $q) {
                    $data = (string) $image->encodeByExtension($codec['ext'], quality: $q);
                    if (strlen($data) <= 200 * 1024) { // ≤ 200 KB
                        $encoded = [
                            'bytes' => $data,
                            'ext'   => $codec['ext'],
                            'mime'  => $codec['mime'],
                        ];
                        break 2;
                    }
                }
            }

            if (!$encoded) {
                throw ValidationException::withMessages([
                    'file' => 'Image is too large to compress under 200 KB. Please upload a smaller image.',
                ]);
            }

            $originalName = pathinfo($uploaded->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = Str::slug($originalName, '_') . '_' . time() . '.' . $encoded['ext'];

            $path = $dir . '/' . $safeName;

            // Save encoded bytes to public disk
            Storage::disk('public')->put($path, $encoded['bytes'], 'public');
        } else {
            // PDFs: store using storeAs (controlled filename)
            $original = $uploaded->getClientOriginalName();
            $nameOnly = pathinfo($original, PATHINFO_FILENAME);
            $safeName = Str::slug($nameOnly, '_') . '_' . time() . '.' . $uploaded->getClientOriginalExtension();

            // storeAs returns the path relative to disk root (e.g. applications/1/docs/filename.pdf)
            $path = $uploaded->storeAs($dir, $safeName, 'public');
        }

        // Create DB record
        $doc = $application->documents()->create([
            'user_id'       => auth()->id(),
            'category'      => $category,
            'path'          => $path,
            'original_name' => $uploaded->getClientOriginalName(),
            'size'          => Storage::disk('public')->size($path),
        ]);

        return response()->json([
            'id'        => $doc->id,
            'url'       =>  asset('storage/'.$doc->path ?? ''),
            'is_image'  => $isImage,
            'file_name' => $doc->original_name,
            'category'  => $category,
        ]);
    }
    public function storegalti(Application $application, Request $r)
    {

        $r->validate([
            'file'     => 'required|file',
            'category' => 'required|string|max:50',
        ]);

        $uploaded = $r->file('file');
        $category = $r->input('category');

        // Enforce single/multiple rules
        if ($category === 'property_photos') {
            // property photos allowed up to 5
            $existingPhotosCount = $application->documents()
                ->where('category', 'property_photos')
                ->count();

            if ($existingPhotosCount >= 5) {
                throw ValidationException::withMessages([
                    'file' => 'Maximum 5 property photos allowed. Please delete existing photos to upload new ones.',
                ]);
            }
        }

        $mime = $uploaded->getMimeType();
        $isImage = Str::startsWith($mime, 'image/');

        // Storage folder
        $dir = "applications/{$application->id}/docs";

        // Build safe filename (storeAs)
        $originalName = $uploaded->getClientOriginalName();
        $nameOnly = pathinfo($originalName, PATHINFO_FILENAME);
        $slug = Str::slug($nameOnly);
        $timestamp = now()->format('Ymd_His');
        $unique = Str::random(6);

        if ($isImage) {
            // Attempt to compress and encode so that file size <= 200KB, prefer webp then jpeg
            // Start by reading with Intervention Image
            $img = Image::make($uploaded->getRealPath())->orientate();

            // Optional: resize very large images keeping aspect ratio (max dimension 2000)
            $img->resize(2000, 2000, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $attempts = [
                ['ext' => 'webp', 'quality' => [80, 70, 60, 50, 40, 35, 30]],
                ['ext' => 'jpg',  'quality' => [90, 80, 70, 60, 50, 45, 40]],
            ];

            $encodedBytes = null;
            $chosenExt = null;

            foreach ($attempts as $attempt) {
                foreach ($attempt['quality'] as $q) {
                    try {
                        $data = (string) $img->encode($attempt['ext'], $q);
                    } catch (\Throwable $e) {
                        continue;
                    }
                    if (strlen($data) <= 200 * 1024) { // <= 200 KB
                        $encodedBytes = $data;
                        $chosenExt = $attempt['ext'];
                        break 2;
                    }
                }
            }

            // If failed to compress under 200KB, try progressive lowering of dimensions
            if (!$encodedBytes) {
                $width = $img->width();
                $height = $img->height();
                $scaleAttempts = [1600, 1400, 1200, 1000, 800, 600];

                foreach ($scaleAttempts as $maxDim) {
                    $tmp = Image::make($uploaded->getRealPath())->orientate();
                    $tmp->resize($maxDim, $maxDim, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    foreach ($attempts as $attempt) {
                        foreach ($attempt['quality'] as $q) {
                            try {
                                $data = (string) $tmp->encode($attempt['ext'], $q);
                            } catch (\Throwable $e) {
                                continue;
                            }
                            if (strlen($data) <= 200 * 1024) {
                                $encodedBytes = $data;
                                $chosenExt = $attempt['ext'];
                                break 3;
                            }
                        }
                    }
                }
            }

            if (!$encodedBytes) {
                throw ValidationException::withMessages([
                    'file' => 'Image too large to compress below 200 KB. Please upload a smaller image.',
                ]);
            }

            $safeName = "{$slug}_{$timestamp}_{$unique}.{$chosenExt}";
            $path = "{$dir}/{$safeName}";

            Storage::disk('public')->put($path, $encodedBytes);
        } else {
            // PDF or other docs: max 20MB
            if ($uploaded->getSize() > 20 * 1024 * 1024) {
                throw ValidationException::withMessages([
                    'file' => 'PDF exceeds 20 MB size limit.',
                ]);
            }

            // keep original extension and use storeAs
            $ext = $uploaded->getClientOriginalExtension();
            $safeName = "{$slug}_{$timestamp}_{$unique}.{$ext}";
            $path = $uploaded->storeAs($dir, $safeName, 'public');
        }

        // Save DB record
        $doc = $application->documents()->create([
            'user_id'       => auth()->id(),
            'category'      => $category,
            'path'          => $path,
            'original_name' => $originalName,
            'size'          => (int) Storage::disk('public')->size($path),
        ]);

        return response()->json([
            'id'        => $doc->id,
            'url'       => Storage::disk('public')->url($path),
            'is_image'  => $isImage,
            'file_name' => $doc->original_name,
            'category'  => $category,
        ]);
    }
    public function store12345(Application $application, Request $r)
    {
        $r->validate([
            'file'     => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:20480', // 20 MB cap
            'category' => 'required|string|max:50',
        ]);

        $uploaded = $r->file('file');
        $category = $r->input('category');
        $isImage  = str_starts_with($uploaded->getMimeType(), 'image/');

        // Handle property_photos differently - allow multiple files
        if ($category === 'property_photos') {
            // Check if this is the 6th photo
            $existingPhotosCount = $application->documents()
                ->where('category', 'property_photos')
                ->count();

            if ($existingPhotosCount >= 5) {
                throw ValidationException::withMessages([
                    'file' => 'Maximum 5 property photos allowed. Please delete existing photos to upload new ones.',
                ]);
            }
        }

        if ($isImage) {
            // Re-encode and compress to ensure ≤ 200 KB
            $image = Image::read($uploaded->getRealPath())
                ->scaleDown(2000, 2000); // safety cap for huge photos

            $attempts = [
                ['ext' => 'webp', 'mime' => 'image/webp', 'qualities' => [80, 70, 60, 50, 40, 35, 30]],
                ['ext' => 'jpg',  'mime' => 'image/jpeg', 'qualities' => [80, 70, 60, 50, 40, 35, 30]],
            ];

            $encoded = null;
            foreach ($attempts as $codec) {
                foreach ($codec['qualities'] as $q) {
                    $data = (string) $image->encodeByExtension($codec['ext'], quality: $q);
                    if (strlen($data) <= 200 * 1024) { // ≤ 200 KB
                        $encoded = [
                            'bytes' => $data,
                            'ext'   => $codec['ext'],
                            'mime'  => $codec['mime'],
                        ];
                        break 2;
                    }
                }
            }

            if (!$encoded) {
                throw ValidationException::withMessages([
                    'file' => 'Image is too large to compress under 200 KB. Please upload a smaller image.',
                ]);
            }

            $dir  = "applications/{$application->id}/docs";
            $name = pathinfo($uploaded->getClientOriginalName(), PATHINFO_FILENAME);
            $safe = str($name)->slug('_') . '_' . time() . '.' . $encoded['ext']; // Add timestamp for uniqueness

            $path = $dir . '/' . $safe;
            Storage::disk('public')->put($path, $encoded['bytes'], 'public');
        } else {
            // PDFs: store as-is (up to 20 MB)
            $path = $uploaded->store("applications/{$application->id}/docs", 'public');
        }

        $doc = $application->documents()->create([
            'user_id'       => auth()->id(),
            'category'      => $category,
            'path'          => $path,
            'original_name' => $uploaded->getClientOriginalName(),
            'size'          => Storage::disk('public')->size($path),
        ]);

        return response()->json([
            'id'        => $doc->id,
            'url'       => Storage::disk('public')->url($path),
            'is_image'  => $isImage,
            'file_name' => $doc->original_name,
            'category'  => $category,
        ]);
    }
    public function store56(Application $application, Request $r)
    {
        $r->validate([
            'file'     => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:20480', // 20 MB cap
            'category' => 'required|string|max:50',
        ]);

        $uploaded = $r->file('file');
        $category = $r->input('category');
        $isImage  = str_starts_with($uploaded->getMimeType(), 'image/');

        if ($isImage) {
            // Re-encode and compress to ensure ≤ 200 KB
            $image = Image::read($uploaded->getRealPath())
                ->scaleDown(2000, 2000); // safety cap for huge photos

            $attempts = [
                ['ext' => 'webp', 'mime' => 'image/webp', 'qualities' => [80, 70, 60, 50, 40, 35, 30]],
                ['ext' => 'jpg',  'mime' => 'image/jpeg', 'qualities' => [80, 70, 60, 50, 40, 35, 30]],
            ];

            $encoded = null;
            foreach ($attempts as $codec) {
                foreach ($codec['qualities'] as $q) {
                    $data = (string) $image->encodeByExtension($codec['ext'], quality: $q);
                    if (strlen($data) <= 200 * 1024) { // ≤ 200 KB
                        $encoded = [
                            'bytes' => $data,
                            'ext'   => $codec['ext'],
                            'mime'  => $codec['mime'],
                        ];
                        break 2;
                    }
                }
            }

            if (!$encoded) {
                throw ValidationException::withMessages([
                    'file' => 'Image is too large to compress under 200 KB. Please upload a smaller image.',
                ]);
            }

            $dir  = "applications/{$application->id}/docs";
            $name = pathinfo($uploaded->getClientOriginalName(), PATHINFO_FILENAME);
            $safe = str($name)->slug('_') . '.' . $encoded['ext'];

            $path = $dir . '/' . $safe;
            Storage::disk('public')->put($path, $encoded['bytes'], 'public');
        } else {
            // PDFs: store as-is (up to 20 MB)
            $path = $uploaded->store("applications/{$application->id}/docs", 'public');
        }

        $doc = $application->documents()->create([
            'user_id'       => auth()->id(),
            'category'      => $category,
            'path'          => $path,
            'original_name' => $uploaded->getClientOriginalName(),
            'size'          => Storage::disk('public')->size($path),
        ]);

        return response()->json([
            'id'        => $doc->id,
            'url'       => Storage::disk('public')->url($path),
            'is_image'  => $isImage,
            'file_name' => $doc->original_name,
        ]);
    }
    public function store123(Application $application, Request $r)
    {
        $r->validate([
            'file'     => 'required|file|max:20480|mimes:pdf,jpg,jpeg,png,webp',
            'category' => 'required|string|max:50',
        ]);

        $path = $r->file('file')->store("applications/{$application->id}/docs", 'public');
        $doc = $application->documents()->create([
            'user_id'       => auth()->id(),
            'category'      => $r->string('category'),
            'path'          => $path,
            'original_name' => $r->file('file')->getClientOriginalName(),
            'size'          => $r->file('file')->getSize(),
        ]);

        return response()->json([
            'id'  => $doc->id,
            'url' => Storage::disk('public')->url($path),
        ]);
    }

    public function destroy(Application $application, Document $document)
    {
        // Ensure the document belongs to given application
        abort_if($document->application_id !== $application->id, 403);

        // Normalize stored path (remove leading slash if any)
        $path = ltrim($document->path, '/');

        try {
            $disk = Storage::disk('public');

            // If file exists exactly as stored -> delete
            if ($disk->exists($path)) {
                $disk->delete($path);
            } else {
                // Try common alternate extensions (useful when images were re-encoded to webp/jpg)
                $dir  = pathinfo($path, PATHINFO_DIRNAME);
                $base = pathinfo($path, PATHINFO_FILENAME);

                // list of possible extensions to try (order matters: prefer image variants first)
                $candidates = ['webp', 'jpg', 'jpeg', 'png', 'pdf'];

                foreach ($candidates as $ext) {
                    $alt = ($dir === '.' ? $base : "$dir/$base") . '.' . $ext;
                    if ($disk->exists($alt)) {
                        $disk->delete($alt);
                        break;
                    }
                }
            }

            // After file deletion, attempt to cleanup the folder if empty
            // (only if we have a directory path)
            $dir = pathinfo($path, PATHINFO_DIRNAME);
            if ($dir && $dir !== '.' && empty($disk->files($dir))) {
                $disk->deleteDirectory($dir);
            }

            // Remove DB record
            $document->delete();
        } catch (\Throwable $e) {
            \Log::error('Failed to delete document', [
                'document_id' => $document->id,
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Failed to delete document. Please try again or contact support.'
            ], 500);
        }

        // 204 No Content
        return response()->noContent();
    }


    public function destroy321(Application $application, Document $document)
    {
        abort_if($document->application_id !== $application->id, 403);

        Storage::disk('public')->delete($document->path);
        $document->delete();

        return response()->noContent();
    }

    public function destroy123(Application $application, Document $document)
    {
        // ensure document belongs to application
        if ($document->application_id !== $application->id) {
            abort(403);
        }

        // delete file if exists
        if ($document->path && Storage::disk('public')->exists($document->path)) {
            Storage::disk('public')->delete($document->path);
        }

        $document->delete();

        return response()->noContent();
    }
}
