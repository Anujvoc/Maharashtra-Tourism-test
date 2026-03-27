<?php

namespace App\Http\Controllers;

use App\Models\AdventureActivityCategory;
use App\Models\WaterActivityType;
use Illuminate\Http\Request;

class WaterActivityTypeController extends Controller
{
    public function index()
    {
        $waterActivities = WaterActivityType::with('category')->latest()->get();
        $categories = AdventureActivityCategory::all();

        return view('backend.service.water-activity-type', compact('waterActivities', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'adventure_activity_categories_id' => 'required|exists:adventure_activity_categories,id',
            'name' => 'required|string|max:255',
        ]);

        WaterActivityType::create($request->all());

        return back()->with('success', 'Water Activity added successfully');
    }

    public function edit($id)
    {
        $waterActivity = WaterActivityType::findOrFail($id);
        $waterActivities = WaterActivityType::with('category')->latest()->get();
        $categories = AdventureActivityCategory::all();

        return view('backend.service.water-activity-type', compact('waterActivity', 'waterActivities', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'adventure_activity_categories_id' => 'required|exists:adventure_activity_categories,id',
            'name' => 'required|string|max:255',
        ]);

        $waterActivity = WaterActivityType::findOrFail($id);
        $waterActivity->update($request->all());

        return redirect()->route('water-activity-type.index')
            ->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        WaterActivityType::findOrFail($id)->delete();

        return back()->with('success', 'Deleted successfully');
    }
}
