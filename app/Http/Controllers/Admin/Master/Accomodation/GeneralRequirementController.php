<?php

namespace App\Http\Controllers\Admin\Master\Accomodation;

use App\Http\Controllers\Controller;
use App\Models\Admin\master\Accomodation\GeneralRequirement;
use Illuminate\Http\Request;

class GeneralRequirementController extends Controller
{

    public function index()
    {
        //
        return view('admin.master.Accomodation.generalRequirement.index');
    }

     public function data()
    {
        $query = GeneralRequirement::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
                $edit = route('admin.master.generalRequirement.edit', $row);

                $delete = route('admin.master.generalRequirement.destroy',$row);
                return '
                    <a href="' . $edit . '" class="btn btn-sm btn-primary me-1">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    ' . '<form action="' . $delete . '" method="POST" style="display:inline-block;margin:0;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this general requirement?\')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>' . '


                ';
            })
            ->rawColumns(['is_active', 'actions'])
            ->make(true);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master.Accomodation.generalRequirement.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
    $validatedData = $request->validate([
        'name'      => 'required|string|max:255|unique:general_requirements,name',
        'is_active' => 'required|boolean',
    ]);

    try {
        GeneralRequirement::create($validatedData);

        return redirect()->route('admin.master.generalRequirement.index')
                         ->with('success', 'General Requirement successfully created!');
    } catch (\Exception $e) {
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while creating the general requirement. Please try again.');
    }
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
    public function edit(string $id)
    {
        $generalRequirement = GeneralRequirement::findOrFail($id);
    return view('admin.master.Accomodation.generalRequirement.edit', compact('generalRequirement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
    $validatedData = $request->validate([
        'name'      => 'required|string|max:255',
        'is_active' => 'required|boolean',
    ]);

    try {
        // Find the existing enterprise record
        $generalRequirement = GeneralRequirement::findOrFail($id);

        // Update the enterprise with validated data
        $generalRequirement->update($validatedData);

        // Redirect back with success message
        return redirect()->route('admin.master.generalRequirement.index', $id)
                         ->with('success', 'General Requirement successfully updated!');
    } catch (\Exception $e) {
        // Redirect back with error and old input in case of failure
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while updating the general requirement. Please try again.');
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $generalRequirement = GeneralRequirement::findOrFail(id: $id);

        $generalRequirement->delete();
        return redirect()->route('admin.master.generalRequirement.index')->with('success', 'General Requirement deleted.');
    }
}
