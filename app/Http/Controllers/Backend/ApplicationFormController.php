<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use App\Models\Admin\ApplicationForm;

class ApplicationFormController extends Controller
{

    public function index()
    {
        $data['forms'] = ApplicationForm::orderBy('created_at', 'desc');
        return view('admin.ApplicationForms.index', $data);
    }

    public function data()
    {
        $query = \App\Models\Admin\ApplicationForm::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                $src = $row->image ? asset('storage/' . $row->image) : asset('images/no-image.png');
                return '<img src="' . $src . '" style="height:50px;width:50px;border-radius:6px;border:1px solid #ddd;">';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
                $edit = route('admin.application-forms.edit', $row);
                $delete = route('admin.application-forms.destroy', $row);
                return '
                    <a href="' . $edit . '" class="btn btn-sm btn-primary me-1">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                ';
            })
            ->rawColumns(['image', 'is_active', 'actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.ApplicationForms.add');
    }


    protected function uniqueSlug(string $base): string
    {
        $slug = $base ?: Str::random(8);
        $i = 1;
        while (ApplicationForm::where('slug', $slug)->exists()) {
            $slug = ($base ?: Str::random(8)) . '-' . $i++;
        }
        return $slug;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:100'],
            // 'image'             => ['nullable', 'image', 'max:2048'],
            'image' => ['nullable', 'mimes:jpg,jpeg,png,webp,avif,gif,bmp,svg', 'max:2048'],
            'is_active'         => ['required', 'in:0,1'],
        ]);

        $data['is_active'] = (int) $data['is_active'] === 1;

        $base = Str::slug($data['name']);
        $data['slug'] = $this->uniqueSlug($base);
        $slugForFile = Str::slug($data['name']) ?: 'file';
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');

            $ext = strtolower($file->getClientOriginalExtension() ?: 'bin');

            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'avif', 'gif', 'bmp', 'svg'];
            if (!in_array($ext, $allowed, true)) {
                return back()->withErrors(['image' => 'Unsupported image type.'])->withInput();
            }

            $filename = $slugForFile . '-' . time() . '.' . $ext;

            $data['image'] = $file->storeAs('application_forms', $filename, 'public');
        }

        ApplicationForm::create($data);

        return redirect()
            ->route('admin.application-forms.index')
            ->with('success', 'Application form created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApplicationForm $application_form)
    {
        return view('admin.ApplicationForms.edit', compact('application_form'));
    }

    public function update(Request $request, \App\Models\Admin\ApplicationForm $application_form)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:100'],
            // allow AVIF etc. (safer on Windows/XAMPP)
            'image'             => ['nullable', 'mimetypes:image/jpeg,image/png,image/webp,image/avif,image/gif,image/bmp,image/svg+xml', 'max:2048'],
            'is_active'         => ['required', 'in:0,1'],
            'remove_image'      => ['nullable', 'in:0,1'],
        ]);

        $data['is_active'] = (int)$data['is_active'] === 1;

        if (($request->input('remove_image') === '1') && $application_form->image) {
            if (Storage::disk('public')->exists($application_form->image)) {
                Storage::disk('public')->delete($application_form->image);
            }
            $application_form->image = null;
        }

        // Handle new upload (replaces old)
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // delete old
            if ($application_form->image && Storage::disk('public')->exists($application_form->image)) {
                Storage::disk('public')->delete($application_form->image);
            }

            $slugForFile = Str::slug($data['name']) ?: 'file';
            $ext = strtolower($request->file('image')->getClientOriginalExtension() ?: 'bin');
            $filename = $slugForFile . '-' . time() . '.' . $ext;

            $data['image'] = $request->file('image')->storeAs('application_forms', $filename, 'public');
        }

        $application_form->update($data);

        return redirect()
            ->route('admin.application-forms.index')
            ->with('success', 'Application form updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
