<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    protected $fillable = [
        'application_id',
        'user_id',
        'flats_count',
        'flat_types',
        'has_dustbins',
        'attached_toilet',
        'road_access',
        'food_on_request',
        'payment_cash',
        'payment_upi'
    ];

    protected $casts = [
        'flat_types' => 'array',
        'has_dustbins' => 'boolean',
        'road_access' => 'boolean',
        'food_on_request' => 'boolean',
        'payment_cash' => 'boolean',
        'payment_upi' => 'boolean',
        'attached_toilet' => 'boolean',
    ];
}
