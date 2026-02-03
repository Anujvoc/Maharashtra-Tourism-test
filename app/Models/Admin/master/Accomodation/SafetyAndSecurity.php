<?php

namespace App\Models\Admin\master\Accomodation;

use Illuminate\Database\Eloquent\Model;

class SafetyAndSecurity extends Model
{
    protected $table = 'industrial_safety_and_securities';
     protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
