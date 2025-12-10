<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\ApplicationForm;
use App\Models\frontend\ApplicationForm\Application;
use App\Models\User;
class IndustrialRegistration extends Model
{
    protected $fillable = [
        'user_id','application_form_id','registration_id','slug_id',
        'status','is_apply','submitted_at',
        'email','mobile','hotel_name','hotel_address',
        'company_name','company_address','authorized_person',
        'region','district','applicant_type','pincode',
        'total_area','total_employees','total_rooms',
        'commencement_date','emergency_contact',
        'mseb_consumer_number','star_category',
        'electricity_company','property_tax_dept','water_bill_dept',
        'pan_card_path','aadhaar_card_path','gst_cert_path','fssai_cert_path',
        'business_reg_path','declaration_path','mpcb_cert_path','light_bill_path',
        'fire_noc_path','property_tax_path','star_cert_path','water_bill_path',
        'electricity_bill_path','building_cert_path',
    ];

    public function application()
    {
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
