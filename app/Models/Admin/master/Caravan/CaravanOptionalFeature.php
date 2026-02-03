<?php

namespace App\Models\Admin\master\Caravan;

use Illuminate\Database\Eloquent\Model;

class CaravanOptionalFeature extends Model
{
     protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
