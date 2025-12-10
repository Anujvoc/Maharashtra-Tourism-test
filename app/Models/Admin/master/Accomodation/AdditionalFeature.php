<?php

namespace App\Models\Admin\master\Accomodation;

use Illuminate\Database\Eloquent\Model;

class AdditionalFeature extends Model
{
    protected $table = 'industrial_additional_features';
     protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
