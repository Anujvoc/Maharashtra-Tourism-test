<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'application_id',
        'user_id',
        'category',
        'path',
        'original_name',
        'size'
    ];
}
