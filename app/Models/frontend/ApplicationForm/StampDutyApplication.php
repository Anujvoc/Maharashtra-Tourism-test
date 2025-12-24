<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasWorkflow;
use App\Traits\HasDocuments;
use App\Models\User;

class StampDutyApplication extends Model
{
    use HasFactory, HasWorkflow, HasDocuments;
    protected $guarded = [];

    protected $casts = [
        'progress' => 'array',
        'existed_before' => 'boolean',
        'is_completed' => 'boolean',
        'declaration_accepted' => 'boolean',
        'is_apply' => 'boolean',
        'application_date' => 'date',
        'eligibility_date' => 'date',
    ];

    public function getDocumentMapping()
    {
        return [
            'doc_challan' => 'Challan Copy',
            'doc_affidavit' => 'Affidavit',
            'doc_registration' => 'Registration Certificate',
            'doc_ror' => '7/12 (Right of Record)',
            'doc_land_map' => 'Land Map',
            'doc_dpr' => 'Detailed Project Report (DPR)',
            'doc_agreement' => 'Agreement Copy',
            'doc_construction_plan' => 'Construction Plan',
            'doc_dp_remarks' => 'Development Plan Remarks',
            'signature_path' => 'Authority Signature',
            'stamp_path' => 'Authority Stamp',
        ];
    }
}
