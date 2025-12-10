<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\ApplicationForm;
use App\Models\User;
class EligibilityRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_name',
        'provisional_number',
        'gst_number',
        'entrepreneurs',
        'project_description',
        'commencement_date',
        'operation_details',
        'cost_component',
        'asset_age',
        'ownership',
        'enclosures',
        'other_docs',
        'signature_path',
        'declaration_place',
        'declaration_date',
        'status',
        'is_apply',
        'submitted_at',
        'user_id',
        'registration_id',
        'slug_id',
        'application_form_id',
        'region_id',
        'district_id',
    ];

    protected $casts = [
        'entrepreneurs'     => 'array',
        'cost_component'    => 'array',
        'asset_age'         => 'array',
        'ownership'         => 'array',
        'enclosures'        => 'array',
        'other_docs'        => 'array',
        'is_apply'          => 'boolean',
        'submitted_at'      => 'datetime',
        'commencement_date' => 'date',
        'declaration_date'  => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicationForm()
    {
        return $this->belongsTo(ApplicationForm::class, 'application_form_id');
    }
}
