<?php

namespace App\Http\Controllers\frontend\ApplicationForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ApplicationForm;
use App\Models\frontend\ApplicationForm\Application;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\frontend\ApplicationForm\AdventureApplicationRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Master\Enterprise;
use App\Models\Admin\Master\Category;
use App\Models\frontend\ApplicationForm\AgricultureRegistration;
use App\Models\frontend\ApplicationForm\AdventureApplication;
use App\Models\frontend\ApplicationForm\WomenCenteredTourismRegistration;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;
class WomenCenteredTourismRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'mobile' => 'required|string|max:15',
            'applicant_name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'organisation_type' => 'required|exists:enterprises,id',
            'gender' => 'required|in:male,female',
            'dob' => 'required|date',
            'age' => 'required|integer|min:18',
            'landline' => 'nullable|string|max:15',
            'residential_address' => 'required|string',
            'business_address' => 'required|string',
            'tourism_business_type' => 'required|string|max:255',
            'tourism_business_name' => 'required|string|max:255',
            'aadhar_no' => 'required|string|max:20',
            'pan_no' => 'required|string|max:20',
            'company_pan_no' => 'nullable|string|max:20',
            'caste' => 'required|exists:categories,id',
            'has_udyog_aadhar' => 'nullable|boolean',
            'udyog_aadhar_no' => 'nullable|string|max:50',
            'gst_no' => 'nullable|string|max:20',
            'female_employees' => 'nullable|integer|min:0',
            'total_employees' => 'nullable|integer|min:0',
            'total_project_cost' => 'required|numeric|min:0',
            'project_information' => 'required|string|min:500',
            'bank_account_holder' => 'required|string|max:255',
            'bank_account_no' => 'required|string|max:30',
            'bank_account_type' => 'nullable|string|max:20',
            'bank_name' => 'required|string|max:255',
            'bank_ifsc' => 'required|string|max:20',
            'business_in_operation' => 'nullable|boolean',
            'business_operation_since' => 'nullable|date',
            'business_expected_start' => 'nullable|date',
            'applicant_image' => 'required|image|mimes:jpeg,jpg,png|max:200',
            'applicant_signature' => 'required|image|mimes:jpeg,jpg,png|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle file uploads
            $applicantImagePath = null;
            $applicantSignaturePath = null;

            if ($request->hasFile('applicant_image')) {
                $applicantImagePath = $request->file('applicant_image')->storeAs(
                    'women-tourism/applicant-images',
                    'applicant_image_' . time() . '.' . $request->file('applicant_image')->getClientOriginalExtension(),
                    'public'
                );
            }

            if ($request->hasFile('applicant_signature')) {
                $applicantSignaturePath = $request->file('applicant_signature')->storeAs(
                    'women-tourism/applicant-signatures',
                    'applicant_signature_' . time() . '.' . $request->file('applicant_signature')->getClientOriginalExtension(),
                    'public'
                );
            }



            $nextId = (AgricultureRegistration::max('id') ?? 0) + 1;
            $registration_id = 'WCT-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            $slug_id = Str::uuid();

            // Create registration record
            $registration = WomenCenteredTourismRegistration::create([
                'user_id' => auth()->id(),
                'status' => 'submitted',
                'application_form_id' => $request->id,
                'registration_id' => $registration_id,
                'slug_id' => $slug_id,
                'is_apply' => true,
                'submitted_at' => now(),

                'email' => $request->email,
                'mobile' => $request->mobile,
                'applicant_name' => $request->applicant_name,
                'business_name' => $request->business_name,
                'organisation_type_id' => $request->organisation_type,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'age' => $request->age,
                'landline' => $request->landline,
                'residential_address' => $request->residential_address,
                'business_address' => $request->business_address,
                'tourism_business_type' => $request->tourism_business_type,
                'tourism_business_name' => $request->tourism_business_name,
                'aadhar_no' => $request->aadhar_no,
                'pan_no' => $request->pan_no,
                'company_pan_no' => $request->company_pan_no,
                'caste_id' => $request->caste,
                'has_udyog_aadhar' => $request->has_udyog_aadhar ?? false,
                'udyog_aadhar_no' => $request->udyog_aadhar_no,
                'gst_no' => $request->gst_no,
                'female_employees' => $request->female_employees,
                'total_employees' => $request->total_employees,
                'total_project_cost' => $request->total_project_cost,
                'project_information' => $request->project_information,
                'bank_account_holder' => $request->bank_account_holder,
                'bank_account_no' => $request->bank_account_no,
                'bank_account_type' => $request->bank_account_type,
                'bank_name' => $request->bank_name,
                'bank_ifsc' => $request->bank_ifsc,
                'business_in_operation' => $request->business_in_operation ?? false,
                'business_operation_since' => $request->business_operation_since,
                'business_expected_start' => $request->business_expected_start,
                'applicant_image_path' => $applicantImagePath,
                'applicant_signature_path' => $applicantSignaturePath,
                'status' => 'pending',
            ]);

            return redirect()
            ->route('applications.index')
                ->with('success', 'Your registration has been submitted successfully!');

        } catch (Exception $e) {
            // Delete uploaded files if there was an error
            if (isset($applicantImagePath) && Storage::disk('public')->exists($applicantImagePath)) {
                Storage::disk('public')->delete($applicantImagePath);
            }
            if (isset($applicantSignaturePath) && Storage::disk('public')->exists($applicantSignaturePath)) {
                Storage::disk('public')->delete($applicantSignaturePath);
            }

            return redirect()->back()
                ->with('error',$e->getMessage())
                ->withInput();
            return redirect()->back()
                ->with('error', 'There was an error submitting your registration. Please try again.',$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show4(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
