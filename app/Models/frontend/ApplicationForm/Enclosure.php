<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;

class Enclosure extends Model
{
    protected $fillable = [
        'application_id',
        'user_id',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array'
    ];
}
