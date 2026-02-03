<?php

namespace App\Http\Controllers\Admin\Master\ownershipBusiness;

use App\Http\Controllers\Controller;
use App\Models\Admin\master\ownershipBusiness\OwnershipOfBusiness;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class OwnershipOfBusinessController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return static::middlewares();
    }
    public static function middlewares(): array
    {
        return [
            new Middleware(middleware: 'auth'),
            new Middleware(middleware: 'permission:view ProvisionalArea', only: ['index', 'data']),
            new Middleware(middleware: 'permission:create ProvisionalArea', only: ['store', 'create']),
            new Middleware(middleware: 'permission:edit ProvisionalArea', only: ['update']),
            new Middleware(middleware: 'permission:delete ProvisionalArea', only: ['destroy']),
        ];
    }
    
    public function index()
    {
        return view('admin.master.ownershipBusinessDoc.index');
    }

    public function data()
    {
        $query = OwnershipOfBusiness::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
                $edit = route('admin.business-proof-doc.edit', $row);

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
        return view('admin.master.ownershipBusinessDoc.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Validate the request data
   $validatedData = $request->validate([
    'name'      => 'required|string|max:255|unique:ownership_of_businesses,name|regex:/^[a-zA-Z\s]+$/',
    'is_active' => 'required|boolean',
]);


    try {

        OwnershipOfBusiness::create($validatedData);


        return redirect()->route('admin.business-proof-doc.index')
                         ->with('success', 'Ownership business proof of document successfully created!');
    } catch (\Exception $e) {


        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while creating the Ownership business proof of document. Please try again.' . $e->getMessage());
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
        $ownershipOfBusiness = OwnershipOfBusiness::findOrFail($id);
    return view('admin.master.ownershipBusinessDoc.edit', compact('ownershipOfBusiness'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
    $validatedData = $request->validate([
            'name' => [
            'required',
            'string',
            'max:255|regex:/^[a-zA-Z\s]+$/',
            Rule::unique('ownership_of_businesses')->ignore($id), // Unique name, except for current record
        ],
        'is_active' => 'required|boolean',
    ]);

    try {
        // Find the existing category record
        $ownershipOfBusiness = OwnershipOfBusiness::findOrFail($id);

        // Update the category with validated data
        $ownershipOfBusiness->update($validatedData);

        return redirect()->route('admin.business-proof-doc.index')
                        ->with('success', 'Ownership business proof of document successfully updated!');
    } catch (\Exception $e) {
        return redirect()->back()
                        ->withInput()
                        ->with('error', 'An error occurred while updating the Ownership business proof of document. Please try again.');
    }
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
