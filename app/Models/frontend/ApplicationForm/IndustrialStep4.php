<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin\ApplicationForm;
class IndustrialStep4 extends Model
{
    protected $fillable = [
        'user_id','application_form_id','slug_id','mseb_consumer_no','star_category',
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
