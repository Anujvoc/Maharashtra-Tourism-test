<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasWorkflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\ApplicationForm;
use App\Traits\HasDocuments;
use App\Models\User;

class EligibilityRegistration extends Model
{
    use HasFactory, HasWorkflow, HasDocuments;

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
        'entrepreneurs' => 'array',
        'cost_component' => 'array',
        'asset_age' => 'array',
        'ownership' => 'array',
        'enclosures' => 'array',
        'other_docs' => 'array',
        'is_apply' => 'boolean',
        'submitted_at' => 'datetime',
        'commencement_date' => 'date',
        'declaration_date' => 'date',
    ];

    public function getDynamicDocuments()
    {
        $docs = [];

        // Define Labels explicitly to match Frontend
        $enclosureLabels = [
            'travel_life' => 'Travel for LiFE Certificate',
            'ca_certificate' => 'CA Certificate of Capital Investment',
            'project_report' => 'Project Report',
            'shop_licence' => 'Licence under Shop & Establishment Act / FDA',
            'star_classification' => 'Star Classification Certificate',
            'mpcb_noc' => 'NOC from Maharashtra Pollution Control Board',
            'balance_sheets' => 'Audited Balance Sheets',
            'proof_commercial' => 'Proof of commencement of commercial operation',
            'declaration_commencement' => 'Declaration regarding commencement date',
            'completion_certificate' => 'Completion Certificate',
            'gst_registration' => 'GST Registration Certificate',
            'processing_fee' => 'Processing Fee Challan',
        ];

        // Enclosures
        if (!empty($this->enclosures) && is_array($this->enclosures)) {
            foreach ($this->enclosures as $key => $data) {
                if (!empty($data['file_path'])) {
                    $docs[] = [
                        'key' => "enclosures.{$key}",
                        'label' => $enclosureLabels[$key] ?? ucwords(str_replace('_', ' ', $key)),
                        'file_path' => $data['file_path']
                    ];
                }
            }
        }

        // Other Docs
        if (!empty($this->other_docs) && is_array($this->other_docs)) {
            foreach ($this->other_docs as $index => $data) {
                if (!empty($data['file_path'])) {
                    $docs[] = [
                        'key' => "other_docs.{$index}",
                        'label' => $data['name'] ?? "Other Document " . ($index + 1),
                        'file_path' => $data['file_path']
                    ];
                }
            }
        }

        // Signature
        if (!empty($this->signature_path)) {
            $docs[] = [
                'key' => 'signature_path',
                'label' => 'Signature',
                'file_path' => $this->signature_path
            ];
        }

        return $docs;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicationForm()
    {
        return $this->belongsTo(ApplicationForm::class, 'application_form_id');
    }
}
