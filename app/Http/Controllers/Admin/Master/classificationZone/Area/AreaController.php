<?php

namespace App\Http\Controllers\Admin\Master\classificationZone\Area;

use App\Http\Controllers\Controller;
use App\Models\Admin\master\classificationZone\Area\Area;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class AreaController extends Controller implements HasMiddleware
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
        return view('admin.master.classificationZone.Area.index');
    }


     public function data()
    {
        $query = Area::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })

            ->addColumn('actions', function ($row) {

                $html = '';


                if (Auth::user()->can('edit ProvisionalArea')) {
                    $edit = route('admin.master.area.edit', $row->id);
                    $html .= '
                        <a href="' . $edit . '" class="btn btn-sm btn-primary me-1" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    ';
                }



                // if (auth()->user()->can('delete ProvisionalArea')) {
                //     $delete = route('admin.master.area.destroy', $row->id);
                //     $html .= '
                //         <form action="' . $delete . '" method="POST" class="d-inline"
                //               onsubmit="return confirm(\'Are you sure you want to delete this area?\')">
                //             ' . csrf_field() . method_field('DELETE') . '
                //             <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                //                 <i class="bi bi-trash"></i>
                //             </button>
                //         </form>
                //     ';
                // }

                return $html ?: '-';
            })

            ->rawColumns(['is_active', 'actions'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master.classificationZone.Area.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
   $validatedData = $request->validate([
    'name'      => 'required|string|max:255|unique:areas,name',
    'is_active' => 'required|boolean',
]);


    try {

        Area::create($validatedData);


        return redirect()->route('admin.area.index')
                         ->with('success', 'Area for classification successfully created!');
    } catch (\Exception $e) {


        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while creating the area for classification. Please try again.' . $e->getMessage());
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
        $area = Area::findOrFail($id);
    return view('admin.master.classificationZone.Area.edit', compact('area'));
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
            'max:255',
            Rule::unique('areas')->ignore($id), // Unique name, except for current record
        ],
        'is_active' => 'required|boolean',
    ]);

    try {
        // Find the existing category record
        $area = Area::findOrFail($id);

        // Update the category with validated data
        $area->update($validatedData);

        return redirect()->route('admin.area.index')
                        ->with('success', 'Area for classification successfully updated!');
    } catch (\Exception $e) {
        return redirect()->back()
                        ->withInput()
                        ->with('error', 'An error occurred while updating the Area. Please try again.');
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
