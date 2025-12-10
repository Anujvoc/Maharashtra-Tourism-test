<?php

namespace App\Http\Controllers\Admin\Master\Caravan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Master\Caravan\CaravanAmenity;
use Illuminate\Validation\Rule;



class CaravanAmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.master.caravan.amenities.index');
    }

    public function data()
    {
        $query = CaravanAmenity::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
           
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
            $edit = route('admin.master.amenities.edit', $row->id);
            $delete = route('admin.master.amenities.destroy', $row->id);

            return '
                <a href="' . $edit . '" class="btn btn-sm btn-primary me-1" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="' . $delete . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this caravan amenity?\')">
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
        return view('admin.master.caravan.amenities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the request data
   $validatedData = $request->validate([
    'name'      => 'required|string|max:255|unique:caravan_amenities,name',
    'is_active' => 'required|boolean',
]);


    try {
    
        CaravanAmenity::create($validatedData);

    
        return redirect()->route('admin.master.amenities.index')
                         ->with('success', 'Caravan Amenity successfully created!');
    } catch (\Exception $e) {
              
        
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while creating the caravan amenity. Please try again.' . $e->getMessage());
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
    $caravan_amenity = CaravanAmenity::findOrFail($id);
    return view('admin.master.caravan.amenities.edit', compact('caravan_amenity'));
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
            Rule::unique('caravan_amenities')->ignore($id), // Unique name, except for current record
        ],
        'is_active' => 'required|boolean',
    ]);

    try {
        // Find the existing caravan amenity record
        $caravan_amenity = CaravanAmenity::findOrFail($id);

        // Update the caravan amenity with validated data
        $caravan_amenity->update($validatedData);

        return redirect()->route('admin.master.amenities.index')
                        ->with('success', 'Caravan Amenity successfully updated!');
    } catch (\Exception $e) {
        return redirect()->back()
                        ->withInput()
                        ->with('error', 'An error occurred while updating the caravan amenity. Please try again.');
    }
}

    /**
     * Remove the specified resource from storage.
     */
       public function destroy($id)
    {
       // dd('fhj');
        $caravan_amenity = CaravanAmenity::findOrFail(id: $id);

        $caravan_amenity->delete();
        return redirect()->route('admin.master.amenities.index')->with('success', 'Caravan Amenity deleted.');
    }
}
