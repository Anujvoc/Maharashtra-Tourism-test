<?php

namespace App\Http\Controllers;

use App\Models\ApplicantRegistation;
use Illuminate\Http\Request;

class ApplicantRegistationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applicants = ApplicantRegistation::latest()->get();
        return view('backend.service.applicant-registration', compact('applicants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ApplicantRegistation::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Applicant category added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $applicant = ApplicantRegistation::findOrFail($id);
        $applicants = ApplicantRegistation::latest()->get();

        return view('backend.service.applicant-registration', compact('applicant', 'applicants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $applicant = ApplicantRegistation::findOrFail($id);
        $applicant->update([
            'name' => $request->name
        ]);

        return redirect()->route('applicant-registration.index')
            ->with('success', 'Applicant category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ApplicantRegistation::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Deleted successfully');
    }
}
