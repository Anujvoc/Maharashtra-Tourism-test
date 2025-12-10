<?php

namespace App\Http\Controllers\Admin\Master\Accomodation;

use App\Http\Controllers\Controller;
use App\Models\Admin\master\Accomodation\GuestService;
use Illuminate\Http\Request;


class GuestServiceController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.master.Accomodation.guestService.index');
    }

     public function data()
    {
        $query = GuestService::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
           
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
                $edit = route('admin.master.guestService.edit', $row);
              
                $delete = route('admin.master.guestService.destroy',$row);
                return '
                    <a href="' . $edit . '" class="btn btn-sm btn-primary me-1">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    ' . '<form action="' . $delete . '" method="POST" style="display:inline-block;margin:0;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this guest service?\')">
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
         //resources\views\admin\master\Accomodation\generalRequirement\create.blade.php
        return view('admin.master.Accomodation.guestService.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
    $validatedData = $request->validate([
        'name'      => 'required|string|max:255|unique:guest_services,name',
        'is_active' => 'required|boolean',
    ]);

    try {
        GuestService::create($validatedData);

        return redirect()->route('admin.master.guestService.index')
                         ->with('success', 'Guest Service successfully created!');
    } catch (\Exception $e) {
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while creating the guest service. Please try again.');
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
        $guestService = GuestService::findOrFail($id);
    return view('admin.master.Accomodation.guestService.edit', compact('guestService'));
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
        $guestService = GuestService::findOrFail($id);

        // Update the enterprise with validated data
        $guestService->update($validatedData);

        // Redirect back with success message
        return redirect()->route('admin.master.guestService.index', $id)
                         ->with('success', 'Guest Service successfully updated!');
    } catch (\Exception $e) {
        // Redirect back with error and old input in case of failure
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while updating the guest service. Please try again.');
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $guestService = GuestService::findOrFail(id: $id);

        $guestService->delete();
        return redirect()->route('admin.master.guestService.index')->with('success', 'Guest Service deleted.');
    }
}
