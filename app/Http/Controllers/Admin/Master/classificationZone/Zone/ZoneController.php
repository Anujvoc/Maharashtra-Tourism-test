<?php

namespace App\Http\Controllers\Admin\Master\classificationZone\Zone;

use App\Http\Controllers\Controller;
use App\Models\Admin\master\classificationZone\Area\Area;
use App\Models\Admin\master\classificationZone\Zone\Zone;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ZoneController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return static::middlewares();
    }
    public static function middlewares(): array
    {
        return [
            new Middleware(middleware: 'auth'),
            new Middleware(middleware: 'permission:view ProvisionalZone', only: ['index', 'data']),
            new Middleware(middleware: 'permission:create ProvisionalZone', only: ['store', 'create']),
            new Middleware(middleware: 'permission:edit ProvisionalZone', only: ['update']),
            new Middleware(middleware: 'permission:delete ProvisionalZone', only: ['destroy']),
        ];
    }
    public function index()
    {
        return view('admin.master.classificationZone.Zone.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $areas=Area::get();
        //we will fetch all the records from district table where state is maharshatra as we form will be filled for maharshatra only
        return view('admin.master.classificationZone.Zone.create', compact('areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $data = $request->validate([
        'name'        => 'required|string|max:191|unique:zones,name|regex:/^[a-zA-Z\s]+$/',
        // 'code'        => 'nullable|string|max:50|unique:divisions,code',
        'is_active'   => 'required|boolean',
        'areas'   => 'nullable|array',
        'areas.*' => 'integer|exists:areas,id',
    ]);

    // Convert districts array to JSON
    $data['areas'] = json_encode($request->areas);

    Zone::create($data);

    return redirect()
        ->route('admin.zone.index')
        ->with('success', 'Zone created successfully.');
    }

   public function data()
{
    $query = Zone::query()->latest();

    return \Yajra\DataTables\Facades\DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('is_active', function ($row) {
            return $row->is_active
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';
        })

        ->addColumn('areas_badge', function ($row) {

    $areaNames = $row->areaNames(); // returns collection of names

    if ($areaNames->isEmpty()) {
        return '<span class="badge bg-secondary">No Area</span>';
    }

    $badges = '';
    foreach ($areaNames as $d) {
        $badges .= '<span class="badge bg-info text-dark me-1">' . $d . '</span>';
    }

    return $badges;
})



->addColumn('actions', function ($row) {

    $html = '';

    // Edit permission
    if (Auth::user()->can('edit ProvisionalZone')) {
        $edit = route('admin.master.zone.edit', $row->id);
        $html .= '
            <a href="' . $edit . '" class="btn btn-sm btn-primary me-1" title="Edit">
                <i class="bi bi-pencil-square"></i>
            </a>
        ';
    }

    // Delete permission
    if (Auth::user()->can('delete ProvisionalZone')) {
        $delete = route('admin.master.zone.destroy', $row->id);
        $html .= '
            <form action="' . $delete . '" method="POST" class="d-inline"
                  onsubmit="return confirm(\'Are you sure you want to delete this zone?\')">
                ' . csrf_field() . method_field('DELETE') . '
                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        ';
    }

    return $html ?: '-';
})

        ->rawColumns(['is_active', 'actions','areas_badge'])
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
         $zone = Zone::findOrFail($id);
        $areas= Area::get();

    return view(
        'admin.master.classificationZone.Zone.edit',
        compact('zone', 'areas')
    );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $zone = Zone::findOrFail($id);
    $validated = $request->validate([
        'name' => 'required|string|max:191|unique:zones,name,' . $id,
        'is_active'   => 'required|boolean',
        'areas'   => 'nullable|array',
        'areas.*' => 'integer|exists:areas,id',
        // 'code' => 'nullable|string|max:50|unique:divisions,code,' . $id,
        // 'districts' => 'nullable|array',
        // 'districts.*' => 'integer|exists:districts,id',
        // 'description' => 'nullable|string|max:500',
        // 'is_active' => 'required|boolean',
    ]);

    // Save districts as JSON
    if ($request->has('areas')) {
        $validated['areas'] = json_encode($request->areas);
    } else {
        $validated['areas'] = json_encode([]);
    }

    // Update
    $zone->update($validated);

    return redirect()
        ->route('admin.zone.index')
        ->with('success', 'Zone updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $zone = Zone::findOrFail(id: $id);

        $zone->delete();
        return redirect()->route('admin.zone.index')->with('success', 'Zone deleted.');
    }
}
