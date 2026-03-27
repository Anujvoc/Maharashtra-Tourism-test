<?php

namespace App\Models\Admin\master\Adventure;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ApplicantRegistation extends Model
{
    use HasFactory;

    protected $table = 'applicant_registations';

    protected $fillable = [
        'name',
        'slug',
    ];
}
