<?php

namespace App\Models\Admin\master\Accomodation;

use Illuminate\Database\Eloquent\Model;

class GeneralRequirement extends Model
{
    protected $table = 'industrial_general_requirements';
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
