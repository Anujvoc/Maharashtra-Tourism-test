<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;

class PhotosSignature extends Model
{
    protected $fillable = [
        'application_id',
        'user_id',
        'applicant_image',
        'applicant_signature'
    ];

    
}
