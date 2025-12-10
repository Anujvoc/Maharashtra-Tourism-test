<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;

class AgricultureRegistration extends Model
{
    protected $fillable = [
        'status','is_apply','submitted_at',
        'user_id','registration_id','slug_id','application_form_id',
        'email','mobile','applicant_name','center_name',
        'applicant_type_id','applicant_address','center_address',
        'region_id','district_id','land_description',
        'facility_day_trip','facility_accommodation','facility_recreational_service',
        'facility_play_area_children','facility_adventure_games','facility_rural_games',
        'facility_agricultural_camping','facility_horticulture_product_sale',
        'applicant_live_in_place',
        'activity_green_house','activity_milk_business','activity_fisheries',
        'activity_rop_vatika','activity_animal_bird_rearing',
        'activity_nature_adventure_tourism','activity_other','activity_other_text',
        'center_started_on',
        'file_signature_stamp','file_land_documents','file_registration_certificate',
        'file_authorization_letter','file_pan_card','file_aadhar_card',
        'file_registration_fee_challan','file_electricity_bill',
        'file_food_security_licence','file_building_permission',
        'file_declaration_form','file_zone_certificate',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'is_apply'     => 'boolean',
    ];
}
