<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin\ApplicationForm;

class IndustrialStep2 extends Model
{
    protected $fillable = [
        'user_id','application_form_id','slug_id',
        'min_rooms','room_size_ok','bathroom_size_ok',
        'bathroom_fixtures','full_time_operation','elevators',
        'electricity_availability','emergency_lights','cctv',
        'disabled_access','security_guards',
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
