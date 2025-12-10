<?php

namespace App\Models\frontend\Applicationform;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class ApplicantDetail extends Model
{
    protected $table = 'application_details';
    protected $fillable = [
        'application_id',
        'user_id',
        'name',
        'phone',
        'email',
        'state',
        'district',
        'business_name',
        'business_type',
        'pan',
        'business_pan',
        'aadhaar',
        'udyam',
        'ownership_proof_type',
        'ownership_proof',
        'is_property_rented',
        'operator_name',
        'rental_agreement'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Casts
    protected $casts = [
        'is_property_rented' => 'boolean',
    ];
}
