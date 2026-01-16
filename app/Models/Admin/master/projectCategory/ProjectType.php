<?php

namespace App\Models\Admin\master\projectCategory;

use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
     protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
