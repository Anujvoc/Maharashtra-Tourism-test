<?php

namespace App\Http\Controllers\Admin\Master\Accomodation;

use App\Http\Controllers\Controller;
use App\Models\Admin\master\Accomodation\AdditionalFeature;
use Illuminate\Http\Request;

class AdditionalFeatureController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.master.Accomodation.additionalFeature.index');
    }

     public function data()
    {
        $query = AdditionalFeature::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
           
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
                $edit = route('admin.master.additionalFeature.edit', $row);
              
                $delete = route('admin.master.additionalFeature.destroy',$row);
                return '
                    <a href="' . $edit . '" class="btn btn-sm btn-primary me-1">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    ' . '<form action="' . $delete . '" method="POST" style="display:inline-block;margin:0;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this additional feature?\')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>' . '
                   

                ';
            })
            ->rawColumns(['is_active', 'actions'])
            ->make(true);
    }
 


  
    public function create()
    {
         //dd('ada'); 
         //resources\views\admin\master\Accomodation\additionalFeature\create.blade.php
        return view('admin.master.Accomodation.additionalFeature.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
    $validatedData = $request->validate([
        'name'      => 'required|string|max:255|unique:additional_features,name',
        'is_active' => 'required|boolean',
    ]);

    try {
        AdditionalFeature::create($validatedData);

        return redirect()->route('admin.master.additionalFeature.index')
                         ->with('success', 'Additional Feature successfully created!');
    } catch (\Exception $e) {
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while creating the additional feature. Please try again.');
    }
    }

   
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $additionalFeature = AdditionalFeature::findOrFail($id);
    return view('admin.master.Accomodation.additionalFeature.edit', compact('additionalFeature'));
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
        $additionalFeature = AdditionalFeature::findOrFail($id);

        // Update the enterprise with validated data
        $additionalFeature->update($validatedData);

        // Redirect back with success message
        return redirect()->route('admin.master.additionalFeature.index', $id)
                         ->with('success', 'Additional Feature successfully updated!');
    } catch (\Exception $e) {

        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while updating the additional feature. Please try again.');
    }
    }

   
    public function destroy(string $id)
    {
         $additionalFeature = AdditionalFeature::findOrFail(id: $id);

        $additionalFeature->delete();
        return redirect()->route('admin.master.additionalFeature.index')->with('success', 'Additional Feature deleted.');
    }
}
