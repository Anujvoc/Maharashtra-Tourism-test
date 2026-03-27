<?php

namespace App\Http\Controllers\frontend\Api\ApplicationFormApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\frontend\ApplicationForm\Application;
use App\Models\frontend\ApplicationForm\ApplicantDetail;
use App\Models\frontend\ApplicationForm\PropertyDetail;
use App\Models\frontend\ApplicationForm\Accommodation;
use App\Models\frontend\ApplicationForm\Facility;
use App\Models\frontend\ApplicationForm\PhotosSignature;
use App\Models\frontend\ApplicationForm\Enclosure;
use App\Models\frontend\ApplicationForm\Document;
use App\Models\Admin\ApplicationForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\District;
use App\Models\Country;
use App\Models\State;
use App\Models\Admin\master\Enterprise;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\frontend\Api\ApplicationMovement;
class TourismRegistrationController extends Controller
{
      
    public function index()
    {
        
        $application_form = ApplicationForm::where('is_active', 1)->get();
        if(!$application_form){
            return response()->json([
                'status' => false,
                'message' => 'No Available Application Forms.'
            ], 400);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'All Application Forms.',
                'data' => $application_form,
            ]);
        }
    }

    public function apply(Request $request, $slug)
{
    $validTypes = [
        'registration-of-tourism-villas',
        'registration-of-tourism-apartments',
        'registration-of-homestays',
        'registration-of-vacation-homes'
    ];

     return response()->json([
            'status' => true,
            'data' => $request->all(),
            
        ]);

    if (!in_array($slug, $validTypes)) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid Application type provided.'
        ], 400);
    }

    // Main validation
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:120',
        'phone' => ['required', 'regex:/^[6-9][0-9]{9}$/', 'unique:users,phone'],
        'email' => 'required|email|unique:users,email',
        'aadhaar' => 'nullable|digits:12|unique:users,aadhar',
        'business_name' => 'required|string|max:120',
        'business_type' => 'required|exists:enterprises,id',
        'state' => 'required|string|max:100',
        'district' => 'required|string|max:120',
        'pan' => ['required', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/'],
        'business_pan' => ['nullable', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/'],
        'udyam' => 'nullable|string|max:30',
        'ownership_proof_type' => 'required|string',
        'is_property_rented' => 'required|boolean',
        'operator_name' => 'nullable|string|max:120',
        
        // Property validation
        'property_name' => 'required|string|max:160',
        'geo_link' => 'nullable|string|max:255',
        'address' => 'required|string|max:1000',
        'address_proof_type' => 'required|in:Latest Electricity Bill,Water Bill,Other',
        'total_area_sqft' => 'nullable|integer|min:0',
        'mahabooking_reg_no' => 'nullable|string|max:80',
        'is_operational' => 'required|boolean',
        'operational_since' => 'nullable|integer|min:1900|max:2050',
        'guests_till_march' => 'nullable|integer|min:0',
        'district_id' => 'required|exists:districts,id',
        
        // Accommodation validation
        'flats_count' => 'required|integer|min:1',
        'flat_types' => ['required', 'array', 'min:1'],
        'flat_types.*' => ['required', 'string', 'max:200'],
        'has_dustbins' => 'required|boolean',
        'attached_toilet' => 'required|boolean',
        'road_access' => 'required|boolean',
        'food_on_request' => 'required|boolean',
        'payment_upi' => 'required|boolean',
        
        // Facilities validation
        'facilities' => ['required', 'array', 'min:1'],
        'facilities.*' => ['required', 'integer', 'exists:tourismfacilities,id'],
        'gras_paid' => ['required', 'in:0,1'],
        
        // Files validation
        'ownership_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'rental_agreement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'address_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
        'applicant_signature' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:50'],
        'applicant_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:200'],
        'file' => 'required|array',
        'file.*' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:20480',
        'file.property_photos' => 'required|array|min:5|max:5',
        'file.property_photos.*' => 'required|file|mimes:jpg,jpeg,png,webp|max:20480',
    ], [
        'applicant_signature.max' => 'Signature must be less than 50KB',
        'applicant_image.max' => 'Photo must be less than 200KB',
        'file.property_photos.min' => 'You must upload exactly 5 property photos',
        'file.property_photos.max' => 'You must upload exactly 5 property photos',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    $data = $validator->validated();
    
    DB::beginTransaction();

    try {
        // Get application form
        $form = ApplicationForm::where('is_active', 1)
            ->where('slug', $slug)
            ->first();

        if (!$form) {
            throw new \Exception('Invalid Application Form.');
        }

        // Create user
        $regId = $this->generateUniqueRegistrationId();
        
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['name'],
            'registration_id' => $regId,
            'image' => null,
            'phone' => $data['phone'],
            'email' => $data['email'],
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'is_email_verified' => true,
            'is_phone_verified' => true,
            'is_aadhar_verified' => false,
            'password' => Hash::make($data['phone']),
            'aadhar' => $data['aadhaar'] ?? null,
        ]);

        if (!$user) {
            throw new \Exception('Failed to create user.');
        }

        // Create application
        $application = Application::create([
            'user_id' => $user->id,
            'slug_id' => (string) Str::ulid(),
            'status' => 'draft',
            'is_apply' => false,
            'application_form_id' => $form->id,
            'current_step' => 1,
            'region_id' => $request->region_id ?? null,
            'district_id' => $request->region_district_id ?? null,
            'is_maitri' => 1,
        ]);

        if (!$application) {
            throw new \Exception('Failed to create application.');
        }

        // Save all steps with validation
        if (!$this->saveApplicant($application, $user->id, $request)) {
            throw new \Exception('Failed to save applicant information.');
        }

        if (!$this->saveProperty($application, $request)) {
            throw new \Exception('Failed to save property information.');
        }

        if (!$this->saveAccommodation($application, $request)) {
            throw new \Exception('Failed to save accommodation information.');
        }

        if (!$this->saveFacilities($application, $request)) {
            throw new \Exception('Failed to save facilities information.');
        }

        if (!$this->savePhotos($application, $request)) {
            throw new \Exception('Failed to save photos.');
        }

        if (!$this->saveEnclosures($application, $request)) {
            throw new \Exception('Failed to save enclosures.');
        }

        // Submit application
        if (!$this->submit($application, $request)) {
            throw new \Exception('Failed to submit application.');
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Application created and submitted successfully',
            'application_id' => $application->registration_id ?? $application->slug_id,
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();
        
        \Log::error('Application submission failed: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'user_id' => $user->id ?? null,
            'application_id' => $application->id ?? null
        ]);

        return response()->json([
            'status' => false,
            'message' => 'Application submission failed: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Step 1: Save Applicant
 */
public function saveApplicant(Application $application, $userId, Request $request)
{
    try {
        $applicant = $application->applicant;
        $existingOwnership = optional($applicant)->ownership_proof;
        $existingRental = optional($applicant)->rental_agreement;
        
        $payload = [
            'user_id' => $userId,
            'application_id' => $application->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'state' => $request->state,
            'district' => $request->district,
            'pan' => $request->pan,
            'business_pan' => $request->business_pan,
            'aadhaar' => $request->aadhaar,
            'udyam' => $request->udyam,
            'ownership_proof_type' => $request->ownership_proof_type,
            'is_property_rented' => (bool)$request->is_property_rented,
            'operator_name' => $request->operator_name,
        ];

        // Handle file uploads
        foreach (['ownership_proof', 'rental_agreement'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $ext = $file->getClientOriginalExtension();
                $filename = "{$fileKey}_{$application->id}_" . time() . "." . $ext;
                $folder = "applications/{$application->id}/applicant";
                
                // Delete old file if exists
                if ($existingApplicant = $application->applicant) {
                    $oldFile = $existingApplicant->{$fileKey} ?? null;
                    if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                        Storage::disk('public')->delete($oldFile);
                    }
                }
                
                $path = $file->storeAs($folder, $filename, 'public');
                if (!$path) {
                    throw new \Exception("Failed to upload {$fileKey}");
                }
                $payload[$fileKey] = $path;
            }
        }

        $result = $application->applicant()->updateOrCreate(
            ['application_id' => $application->id],
            $payload
        );

        if (!$result) {
            throw new \Exception('Failed to save applicant record');
        }

        return true;
        
    } catch (\Throwable $e) {
        \Log::error('saveApplicant error: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * Step 2: Save Property
 */
public function saveProperty(Application $application, Request $request)
{
    try {
        $property = $application->property;
        $existingAddress = optional($property)->address_proof;
        
        $payload = [
            'user_id' => $application->user_id,
            'application_id' => $application->id,
            'property_name' => $request->property_name,
            'geo_link' => $request->geo_link ?? null,
            'address' => $request->address,
            'address_proof_type' => $request->address_proof_type,
            'total_area_sqft' => $request->total_area_sqft ?? null,
            'mahabooking_reg_no' => $request->mahabooking_reg_no ?? null,
            'is_operational' => (bool)$request->is_operational,
            'operational_since' => $request->operational_since ?? null,
            'guests_till_march' => $request->guests_till_march ?? null,
            'district_id' => $request->district_id,
        ];

        // Handle address proof upload
        if ($request->hasFile('address_proof')) {
            $file = $request->file('address_proof');
            $ext = $file->getClientOriginalExtension();
            $dir = "applications/{$application->id}/property";
            $filename = 'address_proof_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $ext;
            
            // Delete old file
            if ($existingAddress && Storage::disk('public')->exists($existingAddress)) {
                Storage::disk('public')->delete($existingAddress);
            }
            
            $path = $file->storeAs($dir, $filename, 'public');
            if (!$path) {
                throw new \Exception('Failed to upload address proof');
            }
            $payload['address_proof'] = $path;
        }

        $result = $application->property()->updateOrCreate(
            ['application_id' => $application->id],
            $payload
        );

        if (!$result) {
            throw new \Exception('Failed to save property record');
        }

        return true;
        
    } catch (\Throwable $e) {
        \Log::error('saveProperty error: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * Step 3: Save Accommodation
 */
public function saveAccommodation(Application $application, Request $request)
{
    try {
        // Process flat types
        $types = $request->flat_types;
        if (!is_array($types)) {
            $types = [$types];
        }

        $types = array_values(array_unique(array_filter(array_map(function ($v) {
            return is_string($v) ? trim($v) : '';
        }, $types))));

        if (count($types) === 0) {
            throw new \Exception('Please add at least one flat/room type.');
        }

        $payload = [
            'user_id' => $application->user_id,
            'application_id' => $application->id,
            'flats_count' => (int) $request->flats_count,
            'flat_types' => $types,
            'has_dustbins' => (bool) $request->has_dustbins,
            'attached_toilet' => (bool) $request->attached_toilet,
            'road_access' => (bool) $request->road_access,
            'food_on_request' => (bool) $request->food_on_request,
            'payment_upi' => (bool) $request->payment_upi,
        ];

        $result = $application->accommodation()->updateOrCreate(
            ['application_id' => $application->id],
            $payload
        );

        if (!$result) {
            throw new \Exception('Failed to save accommodation record');
        }

        return true;
        
    } catch (\Throwable $e) {
        \Log::error('saveAccommodation error: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * Step 4: Save Facilities
 */
public function saveFacilities(Application $application, Request $request)
{
    try {
        $facilityIds = collect($request->facilities)
            ->map(fn($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($facilityIds)) {
            throw new \Exception('Please select at least one facility.');
        }

        $result = $application->facilities()->updateOrCreate(
            ['application_id' => $application->id],
            [
                'facilities' => $facilityIds,
                'gras_paid' => (int) $request->gras_paid,
                'user_id' => $application->user_id,
            ]
        );

        if (!$result) {
            throw new \Exception('Failed to save facilities record');
        }

        return true;
        
    } catch (\Throwable $e) {
        \Log::error('saveFacilities error: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * Step 5: Save Photos
 */
public function savePhotos(Application $application, Request $request)
{
    try {
        $existing = $application->photos;
        $payload = [
            'user_id' => $application->user_id,
            'application_id' => $application->id,
        ];

        // Save signature
        if ($request->hasFile('applicant_signature')) {
            $file = $request->file('applicant_signature');
            $ext = $file->getClientOriginalExtension();
            $dir = "applications/{$application->id}/photos";
            $filename = "signature_{$application->id}_" . time() . "." . $ext;
            
            // Delete old file
            if ($existing && $existing->applicant_signature) {
                $this->deleteFileIfExists($existing->applicant_signature);
            }
            
            $path = $file->storeAs($dir, $filename, 'public');
            if (!$path) {
                throw new \Exception('Failed to upload signature');
            }
            $payload['applicant_signature'] = $path;
        }

        // Save photo
        if ($request->hasFile('applicant_image')) {
            $file = $request->file('applicant_image');
            $ext = $file->getClientOriginalExtension();
            $dir = "applications/{$application->id}/photos";
            $filename = "photo_{$application->id}_" . time() . "." . $ext;
            
            // Delete old file
            if ($existing && $existing->applicant_image) {
                $this->deleteFileIfExists($existing->applicant_image);
            }
            
            $path = $file->storeAs($dir, $filename, 'public');
            if (!$path) {
                throw new \Exception('Failed to upload photo');
            }
            $payload['applicant_image'] = $path;
        }

        // Only update if there are files to save
        if (!empty($payload)) {
            $result = $application->photos()->updateOrCreate(
                ['application_id' => $application->id],
                $payload
            );

            if (!$result) {
                throw new \Exception('Failed to save photos record');
            }
        }

        return true;
        
    } catch (\Throwable $e) {
        \Log::error('savePhotos error: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * Step 6: Save Enclosures
 */
public function saveEnclosures(Application $application, Request $request)
{
    try {
        $savedCategories = [];

        foreach ($request->file('file') as $category => $files) {
            // Convert single file to array
            if (!is_array($files)) {
                $files = [$files];
            }

            // Validate property photos count
            if ($category === 'property_photos' && count($files) !== 5) {
                throw new \Exception('Exactly 5 property photos are required');
            }

            foreach ($files as $uploaded) {
                if (!$uploaded || !$uploaded->isValid()) {
                    throw new \Exception("Invalid file in category: {$category}");
                }

                $mime = $uploaded->getMimeType();
                $isImage = str_starts_with($mime, 'image/');
                $dir = "applications/{$application->id}/docs";

                if ($isImage) {
                    // Process image with Intervention Image
                    try {
                        $image = Image::read($uploaded->getRealPath())
                            ->scaleDown(2000, 2000);

                        $encoded = null;
                        foreach ([80, 70, 60, 50, 40, 30] as $q) {
                            $data = (string) $image->encodeByExtension('webp', quality: $q);
                            if (strlen($data) <= 200 * 1024) {
                                $encoded = $data;
                                break;
                            }
                        }

                        if (!$encoded) {
                            throw new \Exception('Image too large (must be <200KB after compression)');
                        }

                        $name = Str::slug(pathinfo($uploaded->getClientOriginalName(), PATHINFO_FILENAME))
                            . '_' . time() . '_' . uniqid() . '.webp';
                        $path = $dir . '/' . $name;

                        if (!Storage::disk('public')->put($path, $encoded)) {
                            throw new \Exception('Failed to save processed image');
                        }
                    } catch (\Exception $e) {
                        throw new \Exception("Image processing failed for {$category}: " . $e->getMessage());
                    }
                } else {
                    // Handle PDF files
                    $name = Str::slug(pathinfo($uploaded->getClientOriginalName(), PATHINFO_FILENAME))
                        . '_' . time() . '_' . uniqid() . '.' . $uploaded->getClientOriginalExtension();
                    
                    $path = $uploaded->storeAs($dir, $name, 'public');
                    if (!$path) {
                        throw new \Exception("Failed to upload file for {$category}");
                    }
                }

                // Save to database
                $document = $application->documents()->create([
                    'user_id' => $application->user_id,
                    'application_id' => $application->id,
                    'category' => $category,
                    'path' => $path,
                    'original_name' => $uploaded->getClientOriginalName(),
                    'size' => Storage::disk('public')->size($path),
                ]);

                if (!$document) {
                    throw new \Exception("Failed to save document record for {$category}");
                }

                $savedCategories[] = $category;
            }
        }

        // Save enclosure meta
        $uniqueCategories = array_values(array_unique($savedCategories));
        $requiredCats = ['aadhar', 'pan', 'business_reg', 'ownership', 'property_photos', 'character', 'society_noc', 'building_perm', 'gras_copy', 'undertaking'];
        
        // Check if all required categories are present
        $missingCats = array_diff($requiredCats, $uniqueCategories);
        if (!empty($missingCats)) {
            throw new \Exception('Missing required documents: ' . implode(', ', $missingCats));
        }

        $enclosure = $application->enclosures()->updateOrCreate(
            ['application_id' => $application->id],
            [
                'user_id' => $application->user_id,
                'meta' => $uniqueCategories
            ]
        );

        if (!$enclosure) {
            throw new \Exception('Failed to save enclosure meta');
        }

        return true;
        
    } catch (\Throwable $e) {
        \Log::error('saveEnclosures error: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * Step 7: Submit Application
 */
public function submit(Application $application, Request $request)
{
    try {
        // Check all required relationships exist
        $requiredRelations = ['applicant', 'property', 'accommodation', 'facilities', 'photos', 'enclosures'];
        foreach ($requiredRelations as $relation) {
            if (!$application->$relation) {
                throw new \Exception("Application step '{$relation}' is incomplete");
            }
        }

        // Check required documents
        $requiredCats = ['aadhar', 'pan', 'business_reg', 'ownership', 'property_photos', 'character', 'society_noc', 'building_perm', 'gras_copy', 'undertaking'];
        $existingDocs = $application->documents()->pluck('category')->unique()->toArray();
        
        $missingCats = array_diff($requiredCats, $existingDocs);
        if (!empty($missingCats)) {
            throw new \Exception('Missing required documents: ' . implode(', ', $missingCats));
        }

        // Check property photos count
        $propertyPhotosCount = $application->documents()->where('category', 'property_photos')->count();
        if ($propertyPhotosCount < 5) {
            throw new \Exception('Property photos are incomplete (minimum 5 required). Found: ' . $propertyPhotosCount);
        }

        // Generate registration ID
        $registrationId = 'TV' . now()->format('Ymd') . strtoupper(Str::random(6));

        // Update application
        $updated = $application->update([
            'status' => 'submitted',
            'is_apply' => true,
            'current_step' => 7,
            'registration_id' => $registrationId,
            'submitted_at' => now(),
        ]);

        if (!$updated) {
            throw new \Exception('Failed to update application status');
        }

        // Create movement record
        $movement = ApplicationMovement::create([
            'application_id' => $application->id,
            'desk_number' => 1,
            'officer_name' => 'Clerk',
            'action' => 'Submitted',
            'action_datetime' => now(),
            'remarks'     => 'submitted'
        ]);

        if (!$movement) {
            throw new \Exception('Failed to create movement record');
        }

        return true;
        
    } catch (\Throwable $e) {
        \Log::error('submit error: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * Generate unique registration ID
 */
protected function generateUniqueRegistrationId($prefix = 'MV')
{
    do {
        $id = strtoupper($prefix . '-' . Str::upper(Str::random(8)));
    } while (User::where('registration_id', $id)->exists());
    
    return $id;
}

/**
 * Helper function to delete file if exists
 */
protected function deleteFileIfExists($path)
{
    if ($path && Storage::disk('public')->exists($path)) {
        return Storage::disk('public')->delete($path);
    }
    return true;
}

public function TourismVillasForm()
        {
            $application_form = ApplicationForm::where('is_active', 1)
            ->where('slug','registration-of-tourism-villas')
            ->first();
            if(!$application_form){
                return response()->json([
                    'status' => false,
                    'message' => 'No Available Application Forms.'
                ], 400);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Tourism Villas Form.',
                    'data' => $application_form,
                ]);
            }
        }
      public function TourismApartmentsForm()
     {
    $application_form = ApplicationForm::where('is_active', 1)
    ->where('slug','registration-of-tourism-apartments')
    ->first();
        if(!$application_form){
            return response()->json([
                'status' => false,
                'message' => 'No Available Application Forms.'
            ], 400);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Tourism Apartments Registration Forms.',
                'data' => $application_form,
            ]);
        }
    }
      public function HomestaysForm()
     {
    $application_form = ApplicationForm::where('is_active', 1)
   ->where('slug','registration-of-homestays')
    ->first();
        if(!$application_form){
            return response()->json([
                'status' => false,
                'message' => 'No Available Application Forms.'
            ], 400);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Homestays Registration Forms.',
                'data' => $application_form,
            ]);
        }
    }
      public function VacationHomesForm()
     {
    $application_form = ApplicationForm::where('is_active', 1)
    ->where('slug','registration-of-vacation-homes')
    ->first();
        if(!$application_form){
            return response()->json([
                'status' => false,
                'message' => 'No Available Application Forms.'
            ], 400);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Vacation Homes Registration Forms.',
                'data' => $application_form,
            ]);
        }
    
}

}
