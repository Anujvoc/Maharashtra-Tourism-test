<?php

namespace App\Http\Controllers\Admin\Master\Accomodation;

use App\Http\Controllers\Controller;
use App\Models\Admin\master\Accomodation\SafetyAndSecurity;
use Illuminate\Http\Request;

class SafetyAndSecurityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.master.Accomodation.safetySecurity.index');
    }

     public function data()
    {
        $query = SafetyAndSecurity::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
           
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
                $edit = route('admin.master.safetyAndSecurity.edit', $row);
              
                $delete = route('admin.master.safetyAndSecurity.destroy',$row);
                return '
                    <a href="' . $edit . '" class="btn btn-sm btn-primary me-1">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    ' . '<form action="' . $delete . '" method="POST" style="display:inline-block;margin:0;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this safety and security?\')">
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
         //dd('ada'); 
         //resources\views\admin\master\Accomodation\safetySecurity\create.blade.php
        return view('admin.master.Accomodation.safetySecurity.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
    $validatedData = $request->validate([
        'name'      => 'required|string|max:255|unique:safety_and_securities,name',
        'is_active' => 'required|boolean',
    ]);

    try {
        SafetyAndSecurity::create($validatedData);

        return redirect()->route('admin.master.safetyAndSecurity.index')
                         ->with('success', 'Safety and Security successfully created!');
    } catch (\Exception $e) {
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while creating the safety and security. Please try again.');
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
        $safetyAndSecurity = SafetyAndSecurity::findOrFail($id);
    return view('admin.master.Accomodation.safetySecurity.edit', compact('safetyAndSecurity'));
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
        $safetyAndSecurity = SafetyAndSecurity::findOrFail($id);

        // Update the enterprise with validated data
        $safetyAndSecurity->update($validatedData);

        // Redirect back with success message
        return redirect()->route('admin.master.safetyAndSecurity.index', $id)
                         ->with('success', 'Safety and Security successfully updated!');
    } catch (\Exception $e) {
        // Redirect back with error and old input in case of failure
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while updating the safety and security. Please try again.');
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $safetyAndSecurity = SafetyAndSecurity::findOrFail(id: $id);

        $safetyAndSecurity->delete();
        return redirect()->route('admin.master.safetyAndSecurity.index')->with('success', 'safety and Security deleted.');
    }
}
