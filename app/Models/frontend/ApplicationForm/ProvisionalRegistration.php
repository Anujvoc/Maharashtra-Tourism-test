<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
class ProvisionalRegistration extends Model
{
    use HasFactory;
    protected $table = 'provisional_registrations';
    protected $fillable = [
        'user_id',
        'application_id',
        'registration_id',
        'application_form_id',
        'slug_id',
        'is_apply',
        'submitted_at',
        // Step 1
        'applicant_name',
        'company_name',
        'enterprise_type',
        'aadhar_number',
        'application_category',
        'region_id',
        'district_id',
        // Step 2
        'site_address',
        'udyog_aadhar',
        'gst_number',
        'zone',
        'project_type',
        'expansion_details',
        'entrepreneurs_profile',
        'project_category',
        'other_category',
        'project_subcategory',
        'project_description',
        // Step 3
        'land_area',
        'land_ownership_type',
        'building_ownership_type',
        'project_cost',
        'total_employees',
        'investment_components',
        // Step 4
        'means_of_finance',
        // Step 5
        'enclosures',
        'other_documents',
        // Step 6
        'declaration_accepted',
        'place',
        'date',
        'signature_path',
        // Progress
        'current_step',
        'progress',
        'is_completed',
        'status'
    ];

    protected $casts = [
        'site_address' => 'array',
        'expansion_details' => 'array',
        'entrepreneurs_profile' => 'array',
        'investment_components' => 'array',
        'means_of_finance' => 'array',
        'enclosures' => 'array',
        'other_documents' => 'array',
        'progress' => 'array',
        'declaration_accepted' => 'boolean',
        'is_completed' => 'boolean',
        'is_apply' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function updateProgress($step)
    {
        $progress = $this->progress ?? ['done' => 0, 'total' => 6];

        if ($step > $progress['done']) {
            $progress['done'] = $step;
            $this->progress = $progress;
            $this->current_step = $step + 1 <= 6 ? $step + 1 : 6;
            $this->save();
        }

        return $this;
    }

    public function markAsCompleted()
    {
        $this->is_completed = true;
        $this->current_step = 6;
        $this->progress = ['done' => 6, 'total' => 6];
        $this->status = 'submitted';
        $this->status = 'submitted';
        $this->submitted_at = now();
        $this->save();

        return $this;
    }
}
