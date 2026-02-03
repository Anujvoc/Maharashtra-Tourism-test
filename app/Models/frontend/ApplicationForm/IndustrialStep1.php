<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin\ApplicationForm;

class IndustrialStep1 extends Model
{
    protected $fillable = [
        'user_id','application_form_id','slug_id',
        'email','mobile','hotel_name','hotel_address',
        'company_name','company_address','authorized_person',
        'region','district','applicant_type','pincode',
        'total_area','total_employees','total_rooms',
        'commencement_date','emergency_contact',
        'mseb_consumer_number','star_category',
        'electricity_company','property_tax_dept','water_bill_dept',
    ];

    public function application()
    {
        // slug_id se link
        return $this->belongsTo(Application::class, 'slug_id', 'slug_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicationForm()
    {
        return $this->belongsTo(ApplicationForm::class);
    }
}
