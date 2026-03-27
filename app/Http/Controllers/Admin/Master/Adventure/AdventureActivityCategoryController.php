<?php

namespace App\Http\Controllers;

use App\Models\AdventureActivityCategory;
use Illuminate\Http\Request;

class AdventureActivityCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applicants = AdventureActivityCategory::latest()->get();
        return view('backend.service.adventure-activity-category', compact('applicants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        AdventureActivityCategory::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Applicant category added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $applicant = AdventureActivityCategory::findOrFail($id);
        $applicants = AdventureActivityCategory::latest()->get();

        return view('backend.service.adventure-activity-category', compact('applicant', 'applicants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $applicant = AdventureActivityCategory::findOrFail($id);
        $applicant->update([
            'name' => $request->name
        ]);

        return redirect()->route('adventure-activity-category.index')
            ->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        AdventureActivityCategory::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Deleted successfully');
    }
}
