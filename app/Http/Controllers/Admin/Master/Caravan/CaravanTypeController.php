<?php

namespace App\Http\Controllers\Admin\Master\Caravan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\master\Caravan\CaravanType;
use Illuminate\Validation\Rule;


class CaravanTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.master.caravan.type.index');
    }

    public function data()
    {
        $query = CaravanType::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
           
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
            $edit = route('admin.master.types.edit', $row->id);
            $delete = route('admin.master.types.destroy', $row->id);

            return '
                <a href="' . $edit . '" class="btn btn-sm btn-primary me-1" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="' . $delete . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this caravan type?\')">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
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
       // resources\views\admin\master\caravan\type\create.blade.php
        return view('admin.master.caravan.type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the request data
   $validatedData = $request->validate([
    'name'      => 'required|string|max:255|unique:caravan_types,name',
    'is_active' => 'required|boolean',
]);


    try {
    
        CaravanType::create($validatedData);

    
        return redirect()->route('admin.master.types.index')
                         ->with('success', 'Caravan Type successfully created!');
    } catch (\Exception $e) {
              
        
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while creating the caravan type. Please try again.' . $e->getMessage());
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
    public function edit($id)
{
    $caravan_type = CaravanType::findOrFail($id);
    return view('admin.master.caravan.type.edit', compact('caravan_type'));
}

    /**
     * Update the specified resource in storage.
     */

public function update(Request $request, $id)
{
    // Validate the request data
    $validatedData = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('caravan_types')->ignore($id), // Unique name, except for current record
        ],
        'is_active' => 'required|boolean',
    ]);

    try {
        // Find the existing caravan amenity record
        $caravan_type = CaravanType::findOrFail($id);

        // Update the caravan amenity with validated data
        $caravan_type->update($validatedData);

        return redirect()->route('admin.master.types.index')
                        ->with('success', 'Caravan type successfully updated!');
    } catch (\Exception $e) {
        return redirect()->back()
                        ->withInput()
                        ->with('error', 'An error occurred while updating the caravan type. Please try again.');
    }
}

    /**
     * Remove the specified resource from storage.
     */
       public function destroy($id)
    {
       // dd('fhj');
        $caravan_type = CaravanType::findOrFail(id: $id);

        $caravan_type->delete();
        return redirect()->route('admin.master.types.index')->with('success', ' Caravan Type deleted.');
    }
}
