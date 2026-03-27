<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\master\Divisions;
use App\Models\frontend\Districts;
use App\Models\District;
//we will import the model i.e db information where it is stored model means db

class DivisionController extends Controller
{
   public function division(Request $request){
    $divisions = Divisions::orderBy('name')->get();
            if (!Divisions::exists()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'No Division found.'
                    ]);
                }
                
                return response()->json([
                    'status' => true,
                    'message' => 'All Divisions.',
                    'data' => $divisions,
                ]);
   }
   public function get_Region_District($id)
    {

        $division = Divisions::where('id', $id)->first();
        if (!$division) {
            return response()->json(['error' => 'Division not found'], 404);
        }
        $districtIds = json_decode($division->districts, true);

        if (!is_array($districtIds)) {
            return response()->json(['error' => 'Invalid district data'], 400);
        }

        $districts = District::whereIn('id', $districtIds)
            ->select('id', 'name')
            ->get();

        return response()->json($districts);
    }
    
   public function index()
    {
        $divisions = Divisions::latest()->paginate(15);
        return view('admin.master.divisions.index', compact('divisions'));
    }

    public function create()
    {
        $districts=District::where('state_id',14)->orderBy('name','asc')->get();
        //we will fetch all the records from district table where state is maharshatra as we form will be filled for maharshatra only
        return view('admin.master.divisions.create', compact('districts'));
    }

public function store(Request $request)
{
    $data = $request->validate([
        'name'        => 'required|string|max:191|unique:divisions,name|regex:/^[a-zA-Z\s]+$/',
        'code'        => 'nullable|string|max:50|unique:divisions,code',
        'is_active'   => 'required|boolean',
        'districts'   => 'nullable|array',
        'districts.*' => 'integer|exists:districts,id',
    ]);

    // Convert districts array to JSON
    $data['districts'] = json_encode($request->districts);

    Divisions::create($data);

    return redirect()
        ->route('admin.master.divisions.index')
        ->with('success', 'Division created successfully.');
}


    public function show(Divisions $divisions)
    {
        // load districts for display
       /* $divisions->load('districts');
        return view('admin.master.divisions.show', compact('divisions')); */
    }

  public function edit($id)
{
    $division = Divisions::findOrFail($id);
    $districts=District::where('state_id',14)->orderBy('name','asc')->get();

    return view(
        'admin.master.divisions.edit',
        compact('division', 'districts')
    );
}


public function update(Request $request, $id)
{
    //dd($request->all());
    $division = Divisions::findOrFail($id);
    $validated = $request->validate([
        'name' => 'required|string|max:191|unique:divisions,name|regex:/^[a-zA-Z\s]+$/,' . $id,
        'code' => 'nullable|string|max:50|unique:divisions,code,' . $id,
        'districts' => 'nullable|array',
        'districts.*' => 'integer|exists:districts,id',
        'description' => 'nullable|string|max:500',
        'is_active' => 'required|boolean',
    ]);

    // Save districts as JSON
    if ($request->has('districts')) {
        $validated['districts'] = json_encode($request->districts);
    } else {
        $validated['districts'] = json_encode([]);
    }

    // Update
    $division->update($validated);

    return redirect()
        ->route('admin.master.divisions.index')
        ->with('success', 'Division updated successfully.');
}



    public function destroy($id)
    {
       // dd('fhj');
        $division = Divisions::findOrFail(id: $id);

        $division->delete();
        return redirect()->route('admin.master.divisions.index')->with('success', 'Division deleted.');
    }

public function data()
{
    $query = Divisions::query()->latest();

    return \Yajra\DataTables\Facades\DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('is_active', function ($row) {
            return $row->is_active
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>';
        })

        ->addColumn('districts_badge', function ($row) {

    $districtNames = $row->districtNames(); // returns collection of names

    if ($districtNames->isEmpty()) {
        return '<span class="badge bg-secondary">No Districts</span>';
    }

    $badges = '';
    foreach ($districtNames as $d) {
        $badges .= '<span class="badge bg-info text-dark me-1">' . $d . '</span>';
    }

    return $badges;
})



        ->addColumn('actions', function ($row) {
            $edit = route('admin.master.divisions.edit', $row->id);
            $delete = route('admin.master.divisions.destroy', $row->id);

            return '
                <a href="' . $edit . '" class="btn btn-sm btn-primary me-1" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="' . $delete . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this division?\')">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            ';
        })
        ->rawColumns(['is_active', 'actions','districts_badge'])
        ->make(true);
}


}
