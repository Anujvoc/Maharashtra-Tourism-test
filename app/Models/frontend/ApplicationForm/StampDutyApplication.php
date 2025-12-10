<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
class StampDutyApplication extends Model
{
    protected $fillable = [
        'user_id',
        'registration_id',
        'application_form_id',
        'slug_id',
        'is_apply','submitted_at',
        'current_step',
        'progress',
        'is_completed',
        'status',
        'declaration_accepted',
        'region_id','district_id',
        'company_name','registration_no','application_date',
        'applicant_type','agreement_type',
        'c_address','c_city','c_taluka','c_district','c_state','c_pincode','c_mobile','c_phone','c_email','c_fax',
        'p_address','p_city','p_taluka','p_district','p_state','p_pincode','p_mobile','p_phone','p_email','p_website',
        'estimated_project_cost','proposed_employment','tourism_activities','incentives_availed',
        'existed_before','eligibility_cert_no','eligibility_date','present_status',
        'land_gat','land_village','land_taluka','land_district',
        'area_a','area_b','area_c','area_d','area_e',
        'na_gat','na_village','na_taluka','na_district','na_area',
        'cost_land','cost_building','cost_machinery','cost_electrical','cost_misc','cost_other',
        'project_employment','noc_purpose','noc_authority',
        'name_designation','signature_path','stamp_path',
        'doc_challan','doc_affidavit','doc_registration','doc_ror','doc_land_map','doc_dpr',
        'doc_agreement','doc_construction_plan','doc_dp_remarks',
        'aff_name','aff_company','aff_registered_office','aff_land_area','aff_cts','aff_village','aff_taluka','aff_district',
    ];

    protected $casts = [
        'progress' => 'array',
        'existed_before' => 'boolean',
        'is_completed'   => 'boolean',
        'declaration_accepted' => 'boolean',
        'is_apply' => 'boolean',
        'application_date' => 'date',
        'eligibility_date' => 'date',
    ];
}
