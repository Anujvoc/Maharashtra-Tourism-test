<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'application_id',
        'user_id',
        'kitchen',
        'dining_hall',
        'garden',
        'parking',
        'ev_charging',
        'children_play_area',
        'swimming_pool',
        'wifi',
        'first_aid',
        'fire_safety',
        'water_purifier',
        'rainwater_harvesting',
        'solar_power',
        'facilities',
        'other_renewable',
        'gras_paid'
    ];

    protected $casts = [
        'facilities' => 'array',
        'kitchen' => 'boolean',
        'dining_hall' => 'boolean',
        'garden' => 'boolean',
        'parking' => 'boolean',
        'ev_charging' => 'boolean',
        'children_play_area' => 'boolean',
        'swimming_pool' => 'boolean',
        'wifi' => 'boolean',
        'first_aid' => 'boolean',
        'fire_safety' => 'boolean',
        'water_purifier' => 'boolean',
        'rainwater_harvesting' => 'boolean',
        'solar_power' => 'boolean',
        'other_renewable' => 'boolean',
        'gras_paid' => 'boolean'
    ];
}
