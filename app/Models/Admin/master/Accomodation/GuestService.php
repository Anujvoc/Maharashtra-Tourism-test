<?php

namespace App\Models\Admin\master\Accomodation;

use Illuminate\Database\Eloquent\Model;

class GuestService extends Model
{
    protected $table = 'industrial_guest_services';
     protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
