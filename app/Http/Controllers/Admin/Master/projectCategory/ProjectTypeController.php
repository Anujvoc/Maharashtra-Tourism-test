<?php

namespace App\Http\Controllers\Admin\Master\projectCategory;

use App\Http\Controllers\Controller;
use App\Models\Admin\master\projectCategory\ProjectType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
class ProjectTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return static::middlewares();
    }
    public static function middlewares(): array
    {
        return [
            new Middleware(middleware: 'auth'),
            new Middleware(middleware: 'permission:view ProvisionalProjectType', only: ['index', 'data']),
            new Middleware(middleware: 'permission:create ProvisionalProjectType', only: ['store', 'create']),
            new Middleware(middleware: 'permission:edit ProvisionalProjectType', only: ['update']),
            new Middleware(middleware: 'permission:delete ProvisionalProjectType', only: ['destroy']),
        ];
    }
    public function index()
    {
        return view('admin.master.projectCategory.ptype.index');
    }

    public function data()
    {
        $query = ProjectType::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {

                $html = '';

                if (Gate::allows('edit ProvisionalProjectType')) {
                    $edit = route('admin.master.projectType.edit', $row->id);
                    $html .= '
                        <a href="'.$edit.'" class="btn btn-sm btn-primary me-1">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    ';
                }

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
        return view('admin.master.projectCategory.ptype.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name'      => 'required|string|max:255|unique:project_types,name',
            'is_active' => 'required|boolean',
        ]);


        try {

            ProjectType::create($validatedData);


            return redirect()->route('admin.projectType.index')
                ->with('success', 'Project type successfully created!');
        } catch (\Exception $e) {


            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the project type. Please try again.' . $e->getMessage());
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
        $projectType = ProjectType::findOrFail($id);
        return view('admin.master.projectCategory.ptype.edit', compact('projectType'));
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
                Rule::unique('project_types')->ignore($id), // Unique name, except for current record
            ],
            'is_active' => 'required|boolean',
        ]);

        try {
            // Find the existing category record
            $projectType = ProjectType::findOrFail($id);

            // Update the category with validated data
            $projectType->update($validatedData);

            return redirect()->route('admin.projectType.index')
                ->with('success', 'Project Type successfully updated!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the project type. Please try again.');
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
