<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Traits\HasDocuments;
use App\Traits\HasWorkflow;
use App\Models\frontend\CaravanRegistration\CaravanRegistration;

class Application extends Model
{
    use HasWorkflow, HasDocuments;

    protected $fillable = [
        'user_id',
        'application_form_id',
        'slug_id',
        'status',
        'current_step',
        'registration_id',
        'is_apply',
        'submitted_at'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function applicant()
    {
        return $this->hasOne(ApplicantDetail::class);
    }
    public function property()
    {
        return $this->hasOne(PropertyDetail::class);
    }
    public function accommodation()
    {
        return $this->hasOne(Accommodation::class);
    }
    public function facilities()
    {
        return $this->hasOne(Facility::class);
    }
    public function photos()
    {
        return $this->hasOne(PhotosSignature::class);
    }
    public function enclosures()
    {
        return $this->hasOne(Enclosure::class);
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Relationships for Lazy Sync
    public function provisionalRegistration()
    {
        return $this->hasOne(ProvisionalRegistration::class);
    }
    public function touristVillaRegistration()
    {
        return $this->hasOne(TouristVillaRegistration::class);
    }
    public function tourismApartment()
    {
        return $this->hasOne(TourismApartment::class);
    }
    public function adventureApplication()
    {
        return $this->hasOne(AdventureApplication::class, 'registration_id', 'registration_id');
    }
    public function agricultureRegistration()
    {
        return $this->hasOne(AgricultureRegistration::class, 'registration_id', 'registration_id');
    }
    public function womenCenteredTourismRegistration()
    {
        return $this->hasOne(WomenCenteredTourismRegistration::class, 'registration_id', 'registration_id');
    }
    public function caravanRegistration()
    {
        return $this->hasOne(CaravanRegistration::class, 'registration_id', 'registration_id');
    }
    public function stampDutyApplication()
    {
        return $this->hasOne(StampDutyApplication::class, 'registration_id', 'registration_id');
    }
    public function eligibilityRegistration()
    {
        return $this->hasOne(EligibilityRegistration::class, 'registration_id', 'registration_id');
    }
    public function industrialRegistration()
    {
        return $this->hasOne(IndustrialRegistration::class, 'registration_id', 'registration_id');
    }

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    // public function getProgressAttribute()
    // {
    //     $count = 0;
    //     $steps = 7;
    //     foreach (['applicant', 'property', 'accommodation', 'facilities', 'photos', 'documents', 'enclosures'] as $rel) {
    //         if ($this->{$rel})
    //             $count++;
    //     }
    //     return ['done' => $count, 'total' => $steps];
    // }
    public function getProgressAttribute(): array
    {
        return match ((int) $this->application_form_id) {
            9 => $this->getIndustrialProgress(),
            default => $this->getDefaultProgress(),
        };
    }

    // $app->step_label
    public function getStepLabelAttribute(): string
    {
        return match ((int) $this->application_form_id) {
            9 => $this->getIndustrialStepLabel(),
            default => $this->getDefaultStepLabel(),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | DEFAULT FORM (7-step generic form)
    |--------------------------------------------------------------------------
    */

    // progress for normal form (application_form_id != 9)
    public function getDefaultProgress(): array
    {
        // ye tumhara original wala logic hai
        $relations = [
            'applicant',
            'property',
            'accommodation',
            'facilities',
            'photos',
            'documents',
            'enclosures',
        ];

        $steps = 7;
        $count = 0;

        foreach ($relations as $rel) {
            if ($this->{$rel}) {
                $count++;
            }
        }

        return [
            'done' => $count,
            'total' => $steps,
        ];
    }

    // step label for normal form
    public function getDefaultStepLabel(): string
    {
        $steps = [
            1 => 'Applicant',
            2 => 'Property',
            3 => 'Accommodation',
            4 => 'Facilities',
            5 => 'Photo & Signature',
            6 => 'Enclosures',
            7 => 'Review',
        ];

        return $steps[$this->current_step] ?? 'Step ' . $this->current_step;
    }

    /*
    |--------------------------------------------------------------------------
    | INDUSTRIAL FORM (application_form_id = 9)
    |--------------------------------------------------------------------------
    */

    // progress for industrial form
    public function getIndustrialProgress(): array
    {
        // ⚠️ yahan tum apne actual relation names daalna
        // example ke liye naam diye hain:
        $relations = [
            'industrialBasic',       // step 1
            'industrialFacilities',  // step 2
            'industrialDocuments',   // step 3
            'industrialReview',      // step 4
        ];

        $steps = 4;
        $count = 0;

        foreach ($relations as $rel) {
            if ($this->{$rel}) {
                $count++;
            }
        }

        return [
            'done' => $count,
            'total' => $steps,
        ];
    }

    // step label for industrial form
    public function getIndustrialStepLabel(): string
    {
        $steps = [
            1 => 'Basic Information',
            2 => 'Facilities & Services',
            3 => 'Documents Upload',
            4 => 'Review & Submit',
        ];

        return $steps[$this->current_step] ?? 'Step ' . $this->current_step;
    }
}
