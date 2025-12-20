<?php

namespace App\Models\frontend\Applicationform;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasWorkflow;

class TouristVillaRegistration extends Model
{
    use HasFactory, HasWorkflow;
    protected $fillable = [
        'application_id',
        'user_id',
        'applicant_name',
        'applicant_phone',
        'applicant_email',
        'business_name',
        'business_type',
        'pan_number',
        'business_pan_number',
        'aadhar_number',
        'udyam_aadhar_number',
        'ownership_proof',
        'property_rented',
        'operator_name',
        'rental_agreement_path',

        'property_name',
        'property_address',
        'address_proof',
        'property_coordinates',
        'property_operational',
        'operational_year',
        'guests_hosted',
        'total_area',
        'mahabooking_number',

        'number_of_rooms',
        'room_area',
        'attached_toilet',
        'dustbins',
        'road_access',
        'food_provided',
        'payment_options',

        'facilities',
        'application_fees',
        'status',
    ];

    protected $casts = [
        'property_rented' => 'boolean',
        'property_operational' => 'boolean',
        'attached_toilet' => 'boolean',
        'dustbins' => 'boolean',
        'road_access' => 'boolean',
        'food_provided' => 'boolean',
        'payment_options' => 'boolean',
        'application_fees' => 'boolean',
        'status' => 'boolean',
        'facilities' => 'array',
    ];
}
