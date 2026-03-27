<?php

namespace App\Http\Controllers\Admin\Master\projectCategory;

use App\Http\Controllers\Controller;
use App\Models\Admin\master\projectCategory\ProjectCategory;
use App\Models\Admin\master\projectCategory\ProjectType;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProjectCategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return static::middlewares();
    }
    public static function middlewares(): array
    {
        return [
            new Middleware(middleware: 'auth'),
            new Middleware(middleware: 'permission:view ProvisionalProjectCategory', only: ['index', 'data']),
            new Middleware(middleware: 'permission:create ProvisionalProjectCategory', only: ['store', 'create']),
            new Middleware(middleware: 'permission:edit ProvisionalProjectCategory', only: ['update']),
            new Middleware(middleware: 'permission:delete ProvisionalProjectCategory', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('admin.master.projectCategory.pcategory.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $units= ProjectType::get();

        return view('admin.master.projectCategory.pcategory.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
      public function store(Request $request)
    {
        //dd($request->all());
        $data = $request->validate([
        'name'        => 'required|string|max:191|unique:project_categories,name|regex:/^[a-zA-Z\s]+$/',
        'is_active'   => 'required|boolean',
        'units'   => 'nullable|array',
        'units.*' => 'integer|exists:project_types,id',
    ]);

    // Convert districts array to JSON
    $data['units'] = json_encode($request->units);

    ProjectCategory::create($data);

    return redirect()
        ->route('admin.projectCategory.index')
        ->with('success', 'Project Category created successfully.');
    }

     public function data()
{
    $query = ProjectCategory::query()->latest();

    return \Yajra\DataTables\Facades\DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('is_active', function ($row) {
            return $row->is_active
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';
        })

        ->addColumn('units_badge', function ($row) {

        $unitNames = $row->unitNames(); // returns collection of names

    if ($unitNames->isEmpty()) {
        return '<span class="badge bg-secondary">No Units</span>';
    }

    $badges = '';
    foreach ($unitNames as $d) {
        $badges .= '<span class="badge bg-info text-dark me-1">' . $d . '</span>';
    }

    return $badges;
})



        ->addColumn('actions', function ($row) {
            $edit = route('admin.master.projectCategory.edit', $row->id);
            $delete = route('admin.master.projectCategory.destroy', $row->id);

            return '
                <a href="' . $edit . '" class="btn btn-sm btn-primary me-1" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="' . $delete . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this zone?\')">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            ';
        })
        ->rawColumns(['is_active', 'actions','units_badge'])
        ->make(true);
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
        $projectCategory = ProjectCategory::findOrFail($id);
        $units= ProjectType::get();

    return view(
        'admin.master.projectCategory.pcategory.edit',
        compact('projectCategory', 'units')
    );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
          $projectCategory = ProjectCategory::findOrFail($id);
    $validated = $request->validate([
        'name' => 'required|string|max:191|unique:project_categories,name|regex:/^[a-zA-Z\s]+$/,' . $id,
        'is_active'   => 'required|boolean',
        'units'   => 'nullable|array',
        'units.*' => 'integer|exists:project_types,id',

    ]);


    if ($request->has('units')) {
        $validated['units'] = json_encode($request->units);
    } else {
        $validated['units'] = json_encode([]);
    }

    // Update
    $projectCategory->update($validated);

    return redirect()
        ->route('admin.projectCategory.index')
        ->with('success', 'Project Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
                $projectCategory = ProjectCategory::findOrFail(id: $id);

        $projectCategory->delete();
        return redirect()->route('admin.projectCategory.index')->with('success', 'Project Category deleted.');
    }
}
