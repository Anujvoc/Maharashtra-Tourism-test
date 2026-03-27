<?php

namespace App\Http\Controllers;

use App\Models\AdventureActivity;
use App\Models\AdventureActivityCategory;
use Illuminate\Http\Request;

class AdventureActivityController extends Controller
{
    public function index()
    {
        $activities = AdventureActivity::with('category')->latest()->get();
        $categories = AdventureActivityCategory::all();

        return view('backend.service.adventure-activity', compact('activities', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'adventure_activity_categories_id' => 'required|exists:adventure_activity_categories,id',
            'name' => 'required|string|max:255',
        ]);

        AdventureActivity::create($request->all());

        return back()->with('success', 'Activity added successfully');
    }

    public function edit($id)
    {
        $activity = AdventureActivity::findOrFail($id);
        $activities = AdventureActivity::with('category')->latest()->get();
        $categories = AdventureActivityCategory::all();

        return view('backend.service.adventure-activity', compact('activity', 'activities', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'adventure_activity_categories_id' => 'required|exists:adventure_activity_categories,id',
            'name' => 'required|string|max:255',
        ]);

        $activity = AdventureActivity::findOrFail($id);
        $activity->update($request->all());

        return redirect()->route('adventure-activity.index')
            ->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        AdventureActivity::findOrFail($id)->delete();

        return back()->with('success', 'Deleted successfully');
    }
}
