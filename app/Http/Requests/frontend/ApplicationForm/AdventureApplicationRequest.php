<?php

namespace App\Http\Requests\frontend\ApplicationForm;

use Illuminate\Foundation\Http\FormRequest;

class AdventureApplicationRequest extends FormRequest
{
    public function authorize()
    {

        return true;
    }

    public function rules()
    {
        $maxFile = 5120; // KB
        return [
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'applicant_type' => 'required|string|max:100',
            'applicant_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'applicant_address' => 'required|string',
            'region_id' => 'required|integer',
            'district_id' => 'required|integer',
            'whatsapp' => 'nullable|string|max:20',
            'adventure_category' => 'required|string|max:255',
            'activity_name' => 'required|string|max:255',
            'activity_location' => 'nullable|string',
            'pan_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:' . $maxFile,
            'aadhar_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:' . $maxFile,
            // system fields...
            'status' => 'nullable|string|max:50',
            'is_apply' => 'nullable|boolean',
            'submitted_at' => 'nullable|date',
            'user_id' => 'nullable|integer',
            'registration_id' => 'nullable|integer',
            'slug_id' => 'nullable|string|max:255',
            'application_form_id' => 'nullable|integer',
        ];
    }
}
