<?php

namespace App\Http\Controllers\Admin\Master;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Admin\master\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class CategoryController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return static::middlewares();
    }
    public static function middlewares(): array
    {
        return [
            new Middleware(middleware: 'auth'),
            new Middleware(middleware: 'permission:view category', only: ['index', 'data']),
            new Middleware(middleware: 'permission:create category', only: ['store', 'create']),
            new Middleware(middleware: 'permission:edit category', only: ['update']),
            new Middleware(middleware: 'permission:delete category', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('admin.master.category.index');
    }

      public function data()
    {
        $query = Category::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
                $edit = route('admin.categories.edit', $row);

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
        return view('admin.master.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the request data
   $validatedData = $request->validate([
    'name'      => 'required|string|max:255|unique:categories,name',
    'is_active' => 'required|boolean',
]);


    try {

        Category::create($validatedData);


        return redirect()->route('admin.categories.index')
                         ->with('success', 'Category successfully created!');
    } catch (\Exception $e) {


        return redirect()->back()
                         ->withInput()
                         ->with('error', 'An error occurred while creating the category. Please try again.' . $e->getMessage());
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
    $category = Category::findOrFail($id);
    return view('admin.master.category.edit', compact('category'));
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
            Rule::unique('categories')->ignore($id), // Unique name, except for current record
        ],
        'is_active' => 'required|boolean',
    ]);

    try {
        // Find the existing category record
        $category = Category::findOrFail($id);

        // Update the category with validated data
        $category->update($validatedData);

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category successfully updated!');
    } catch (\Exception $e) {
        return redirect()->back()
                        ->withInput()
                        ->with('error', 'An error occurred while updating the category. Please try again.');
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
