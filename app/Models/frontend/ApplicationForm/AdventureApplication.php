<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasWorkflow;

class AdventureApplication extends Model
{
    use HasFactory, HasWorkflow;
    protected $fillable = [
        'email',
        'mobile',
        'applicant_type',
        'applicant_name',
        'company_name',
        'applicant_address',
        'region_id',
        'district_id',
        'whatsapp',
        'adventure_category',
        'activity_name',
        'activity_location',
        'pan_file',
        'aadhar_file',

        'status',
        'is_apply',
        'submitted_at',

        'user_id',
        'registration_id',
        'slug_id',
        'application_form_id',
    ];

    protected $casts = [
        'is_apply' => 'boolean',
        'submitted_at' => 'datetime',
    ];

}
