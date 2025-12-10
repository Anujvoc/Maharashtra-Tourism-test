<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Master\Enterprise;

class EnterpriseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.master.enterprise.index');
    }

     public function data()
    {
        $query = Enterprise::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
           
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
                $edit = route('admin.enterprises.edit', $row);
                
                return '
                    <a href="' . $edit . '" class="btn btn-sm btn-primary me-1">
                        <i class="bi bi-pencil-square"></i>
                    </a>

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
        //dd('ada');
        return view('admin.master.enterprise.create');
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'name'      => 'required|string|max:255|unique:enterprises,name',
        'is_active' => 'required|boolean',
    ]);

    try {
        Enterprise::create($validatedData);

        return redirect()->route('admin.enterprises.create')
                         ->with('success', 'Enterprise successfully created!');
    } catch (\Exception $e) {
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while creating the enterprise. Please try again.');
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
    $enterprise = Enterprise::findOrFail($id);
    return view('admin.master.enterprise.edit', compact('enterprise'));
}


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    // Validate the request data
    $validatedData = $request->validate([
        'name'      => 'required|string|max:255',
        'is_active' => 'required|boolean',
    ]);

    try {
        // Find the existing enterprise record
        $enterprise = Enterprise::findOrFail($id);

        // Update the enterprise with validated data
        $enterprise->update($validatedData);

        // Redirect back with success message
        return redirect()->route('admin.enterprises.edit', $id)
                         ->with('success', 'Enterprise successfully updated!');
    } catch (\Exception $e) {
        // Redirect back with error and old input in case of failure
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while updating the enterprise. Please try again.');
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
