<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\District;
class PropertyDetail extends Model
{
    protected $fillable = [
        'application_id',
        'user_id',
        'property_name',
        'address',
        'address_proof',
        'address_proof_type',
        'district_id',
        'geo_link',
        'is_operational',
        'operational_since',
        'guests_till_march',
        'total_area_sqft',
        'mahabooking_reg_no'
    ];

    // In Application model
public function applicantDetail()
{
    return $this->hasOne(ApplicantDetail::class);
}

public function propertyDetail()
{
    return $this->hasOne(PropertyDetail::class);
}

// In ApplicantDetail and PropertyDetail models
public function application()
{
    return $this->belongsTo(Application::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}
public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
