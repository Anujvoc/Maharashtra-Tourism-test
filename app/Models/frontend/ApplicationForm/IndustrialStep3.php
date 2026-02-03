<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin\ApplicationForm;
class IndustrialStep3 extends Model
{
    protected $fillable = [
        'user_id','application_form_id','slug_id','additional_features','safety_security',
        'bath_attached','bath_hot_cold','water_saving_taps',
        'public_lobby','reception','public_restrooms',
        'disabled_room','fssai_kitchen','uniforms',
        'pledge_display','complaint_book','nodal_officer',
        'doctor_on_call','police_verification','fire_drills','first_aid',
        'suite','fb_outlet','iron_facility','paid_transport',
        'business_center','conference_facilities','sewage_treatment','rainwater_harvesting',
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
