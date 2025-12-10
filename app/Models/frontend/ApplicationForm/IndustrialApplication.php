<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\ApplicationForm;
use App\Models\User;
class IndustrialApplication extends Model
{
    protected $table = 'industrial_applications';
    protected $fillable = [
        'user_id',
        'application_form_id',
        'slug_id',
        'status',
        'is_apply',
        'current_step',
    ];

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicationForm()
    {
        return $this->belongsTo(ApplicationForm::class);
    }

    public function registration()
    {
        return $this->hasOne(IndustrialRegistration::class, 'slug_id', 'slug_id');
    }

    public function step1()
    {
        return $this->hasOne(IndustrialStep1::class, 'slug_id', 'slug_id');
    }

    public function step2()
    {
        return $this->hasOne(IndustrialStep2::class, 'slug_id', 'slug_id');
    }

    public function step3()
    {
        return $this->hasOne(IndustrialStep3::class, 'slug_id', 'slug_id');
    }

    public function step4()
    {
        return $this->hasOne(IndustrialStep4::class, 'slug_id', 'slug_id');
    }

    protected $appends = ['progress'];

    public function getProgressAttribute(): array
    {
        $total = 4;
        $done = max(0, min($total, $this->current_step - 1));

        return [
            'done'  => $done,
            'total' => $total,
        ];
    }
}

