<?php

namespace App\Models\frontend\Applicationform;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasWorkflow;

class TourismApartment extends Model
{
    use HasWorkflow;
    protected $table = "tourism_apartments";

    protected $fillable = [
        // A) Details of the Applicant
        'name',
        'application_id',
        'user_id',
        'mno',
        'email',
        'business',
        'type',
        'pan',
        'bpan',
        'aadhar',
        'uaadhar',
        'proof',
        'prop',
        'opname',
        'agreement',

        // B) Details of the Property
        'pname',
        'padd',
        'pradd',
        'gc',
        'ops',
        'year',
        'guestno',
        'area',
        'regn',

        // C) Details of the Accommodation
        'fno',
        'ftype',
        'farea',
        'atinfo',
        'dbin',
        'aroad',
        'areq',
        'pay',

        // D) Common Facilities
        'co',
        'ct',
        'cth',
        'cf',
        'cfi',
        'cs',
        'cse',
        'ce',
        'cb',
        'cn',
        'cte',
        'cel',
        'ctw',
        'cthr',

        // E) GRAS Challan
        'paystatus',
        'challan',

        // Signature Section
        'sign',
        'aname',
        'date',
    ];
}
