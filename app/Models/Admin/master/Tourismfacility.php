<?php

namespace App\Models\Admin\Master;

use Illuminate\Database\Eloquent\Model;

class Tourismfacility extends Model
{
     protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
