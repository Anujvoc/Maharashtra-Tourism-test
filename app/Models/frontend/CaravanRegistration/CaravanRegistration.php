<?php

namespace App\Models\frontend\CaravanRegistration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasWorkflow;
use App\Traits\HasDocuments;

use Illuminate\Database\Eloquent\Model;

class CaravanRegistration extends Model
{
    use HasFactory, HasWorkflow, HasDocuments;


    protected $fillable = [
        'email',
        'mobile',
        'applicant_name',
        'address',
        'region_id',
        'district_id',
        'applicant_type',
        'emergency_contact',
        'caravan_type_id',
        'prior_experience',
        'vehicle_reg_no',
        'capacity',
        'beds',
        'engine_no',
        'chassis_no',
        'amenities',
        'optional_features',
        'routes',
        'registration_fee_challan',
        'vehicle_reg_card',
        'vehicle_insurance',
        'declaration_form',
        'aadhar_card',
        'pan_card',
        'vehicle_purchase_copy',
        'company_proof',
        'status',
        'is_apply',
        'submitted_at',
        'user_id',
        'registration_id',
        'slug_id',
        'application_form_id',
    ];


    protected $casts = [
        'amenities' => 'array',
        'optional_features' => 'array',
    ];
}
