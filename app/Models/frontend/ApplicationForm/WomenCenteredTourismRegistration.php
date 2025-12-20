<?php

namespace App\Models\frontend\ApplicationForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasWorkflow;

class WomenCenteredTourismRegistration extends Model
{
    use HasFactory, HasWorkflow;

    protected $fillable = [
        'status',
        'is_apply',
        'submitted_at',
        'registration_id',
        'slug_id',
        'application_form_id',
        'user_id',

        'email',
        'mobile',
        'business_name',
        'organisation_type',
        'applicant_name',
        'gender',
        'dob',
        'age',
        'landline',
        'residential_address',
        'business_address',
        'tourism_business_type',
        'tourism_business_name',
        'aadhar_no',
        'pan_no',
        'company_pan_no',
        'caste',
        'has_udyog_aadhar',
        'udyog_aadhar_no',
        'gst_no',
        'female_employees',
        'total_employees',
        'total_project_cost',
        'project_information',
        'bank_account_holder',
        'bank_account_no',
        'bank_account_type',
        'bank_name',
        'bank_ifsc',
        'business_in_operation',
        'business_operation_since',
        'business_expected_start',
        'applicant_image_path',
        'applicant_signature_path',

    ];

    protected $casts = [
        'dob' => 'date',
        'business_operation_since' => 'date',
        'business_expected_start' => 'date',
        'has_udyog_aadhar' => 'boolean',
        'business_in_operation' => 'boolean',
        'submitted_at' => 'datetime',
        'is_apply' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


