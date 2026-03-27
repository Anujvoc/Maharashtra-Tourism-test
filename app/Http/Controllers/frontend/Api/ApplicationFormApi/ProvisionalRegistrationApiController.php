<?php

namespace App\Http\Controllers\frontend\Api\ApplicationFormApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ApplicationForm;
use Illuminate\Http\UploadedFile;
use App\Models\frontend\Api\ApplicationMovement;
use App\Models\frontend\ApplicationForm\Application;
use App\Models\frontend\ApplicationForm\ApplicantDetail;
use App\Models\frontend\ApplicationForm\PropertyDetail;
use App\Models\frontend\ApplicationForm\Accommodation;
use App\Models\frontend\ApplicationForm\Facility;
use App\Models\frontend\ApplicationForm\PhotosSignature;
use App\Models\frontend\ApplicationForm\ProvisionalRegistration;
use App\Models\frontend\ApplicationForm\Enclosure;
use App\Models\frontend\ApplicationForm\Document;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class ProvisionalRegistrationApiController extends Controller
{
     public function ProvisionalForm()
    {
        
           $application_form = ApplicationForm::where('is_active', 1)
        ->where('slug','issuance-of-temporary-registration-certificate')
        ->first();
        if(!$application_form){
            return response()->json([
                'status' => false,
                'message' => 'No Available Application Forms.'
            ], 400);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Issuance of Provisional Registration Certificate Forms.',
                'data' => $application_form,
            ]);
        }
        
    }

    public function store(Request $request)
{
    // Get the application form
    $form = ApplicationForm::where('is_active', 1)
        ->where('slug', $request->slug)
        ->first();
    
    if (!$form) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid Application type provided.'
        ], 400);
    }
    
    // Validate all data at once
    $validator = Validator::make($request->all(), $this->getCombinedValidationRules());
    
    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }
    
    DB::beginTransaction();
    
    try {
        // Create user
        $regId = $this->generateUniqueRegistrationId();
        $user = User::create([
            'name' => $request->applicant_name,
            'username' => $request->applicant_name,
            'registration_id' => $regId,
            'image' => null,
            'phone' => $request->phone ?? null,
            'email' => $request->email_id ?? null,
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'is_email_verified' => true,
            'is_phone_verified' => true,
            'is_aadhar_verified' => false,
            'password' => Hash::make($request->phone),
            'aadhar' => $request->aadhar_no ?? null,
        ]);
        
        if (!$user) {
            throw new \Exception('Failed to create user');
        }
        
        $userId = $user->id;
        $application_form_id = $form->id;
        
        // Process all data at once
        $registrationData = $this->prepareRegistrationData($request);
        
        // Create provisional registration
        $registration = ProvisionalRegistration::firstOrCreate(
            [
                'application_form_id' => $application_form_id,
                'user_id' => $userId,
                'registration_id' => 'PVR-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
                'slug_id' => 'PVR-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
                'submitted_at' => now(),
            ],
            array_merge($registrationData, [
                'current_step' => 6,
                'progress' => ['done' => 6, 'total' => 6],
                'is_apply' => true,
                'is_maitri' => true,
            ])
        );
        
        // Create application movement record
        ApplicationMovement::updateOrCreate(
            [
                'application_id' => $registration->registration_id,
                'desk_number' => 1,
            ],
            [
                'officer_name' => 'Clerk',
                'remarks' => 'Under review',
                'action' => 'Pending',
                'action_datetime' => now(),
            ]
        );
        
        DB::commit();
        
        return response()->json([
            'status' => true,
            'message' => 'Application submitted successfully',
            'data' => [
                'registration_id' => $registration->registration_id,
                
            ]
        ], 201);
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'status' => false,
            'message' => 'Failed to submit application',
            'error' => $e->getMessage()
        ], 500);
    }
}

private function getCombinedValidationRules()
{
    return [
        // User/General Details
        'email_id' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:10|regex:/^[6-9][0-9]{9}$/',
        'mobile' => 'required|regex:/^[6-9][0-9]{9}$/',
        'email' => 'required|email',
        'applicant_name' => 'required|string|max:255',
        'company_name' => 'required|string|max:255',
        'enterprise_type' => 'required|string',
        'aadhar_number' => 'required|digits:12',
        'application_category' => 'required|string',
        'region_id' => 'required|exists:divisions,id',
        'district_id' => 'required|exists:districts,id',
        
        // Location/Site Details
        'survey_type' => 'required|string',
        'survey_number' => 'required|string',
        'village_city' => 'required|string|max:255',
        'taluka' => 'required|string|max:255',
        'district' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'pincode' => 'required|digits:6',
        'website' => 'nullable|url',
        'udyog_aadhar' => 'nullable|string',
        'gst_number' => 'nullable|string',
        'zone' => 'required|string',
        'project_type' => 'required|string|in:New,Expansion',
        'eligibility_certificate' => 'nullable|string',
        'project_category' => 'required|string',
        'project_subcategory' => 'required|string|max:255',
        'project_description' => 'required|string|min:10',
        
        // Entrepreneur Profile (dynamic array)
        'entre_name' => 'required|array',
        'entre_name.*' => 'required|string',
        'entre_designation' => 'required|array',
        'entre_designation.*' => 'required|string',
        'entre_ownership' => 'required|array',
        'entre_ownership.*' => 'required|string',
        'entre_gender' => 'required|array',
        'entre_gender.*' => 'required|string',
        'entre_age' => 'required|array',
        'entre_age.*' => 'required|integer|min:18|max:100',
        
        // Investment Details
        'land_area' => 'required|numeric|min:1',
        'land_ownership_type' => 'required|string|in:Owned,Leased,Rent',
        'building_ownership_type' => 'required|string|in:Owned,Leased,Rent',
        'project_cost' => 'required|numeric|min:0',
        'total_employees' => 'required|integer|min:1',
        
        // Investment Components
        'land_est' => 'nullable|numeric|min:0',
        'land_inv' => 'nullable|numeric|min:0',
        'building_est' => 'nullable|numeric|min:0',
        'building_inv' => 'nullable|numeric|min:0',
        'machinery_est' => 'nullable|numeric|min:0',
        'machinery_inv' => 'nullable|numeric|min:0',
        'engineering_est' => 'nullable|numeric|min:0',
        'engineering_inv' => 'nullable|numeric|min:0',
        'preop_est' => 'nullable|numeric|min:0',
        'preop_inv' => 'nullable|numeric|min:0',
        'margin_est' => 'nullable|numeric|min:0',
        'margin_inv' => 'nullable|numeric|min:0',
        
        // Means of Finance
        'share_promoters' => 'nullable|numeric|min:0',
        'share_financial' => 'nullable|numeric|min:0',
        'share_public' => 'nullable|numeric|min:0',
        'loan_financial' => 'nullable|numeric|min:0',
        'loan_banks' => 'nullable|numeric|min:0',
        'loan_others' => 'nullable|numeric|min:0',
        
        // Declaration
        'declaration' => 'required|accepted',
        'place' => 'required|string|max:255',
        'date' => 'required|date',
        'signature' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        
        // Expansion details (conditional)
        'existing_facilities' => 'required_if:project_type,Expansion|array',
        'existing_facilities.*' => 'nullable|string',
        'existing_employment' => 'required_if:project_type,Expansion|array',
        'existing_employment.*' => 'nullable|numeric',
        'expansion_facilities' => 'required_if:project_type,Expansion|array',
        'expansion_facilities.*' => 'nullable|string',
        'expansion_employment' => 'required_if:project_type,Expansion|array',
        'expansion_employment.*' => 'nullable|numeric',
    ];
}

private function prepareRegistrationData($request)
{
    // Process site address
    $siteAddress = [
        'survey_type' => $request->survey_type,
        'survey_number' => $request->survey_number,
        'village_city' => $request->village_city,
        'taluka' => $request->taluka,
        'district' => $request->district,
        'state' => $request->state,
        'pincode' => $request->pincode,
        'mobile' => $request->phone,
        'email' => $request->email,
        'website' => $request->website ?? null,
    ];
    
    // Process entrepreneurs profile
    $entrepreneurs = [];
    if ($request->has('entre_name')) {
        for ($i = 0; $i < count($request->entre_name); $i++) {
            $entrepreneurs[] = [
                'name' => $request->entre_name[$i] ?? null,
                'designation' => $request->entre_designation[$i] ?? null,
                'ownership' => $request->entre_ownership[$i] ?? null,
                'gender' => $request->entre_gender[$i] ?? null,
                'age' => $request->entre_age[$i] ?? null,
            ];
        }
    }
    
    // Process expansion details
    $expansionDetails = null;
    if ($request->project_type === 'Expansion' && $request->has('existing_facilities')) {
        $expansionDetails = [];
        for ($i = 0; $i < count($request->existing_facilities); $i++) {
            $expansionDetails[] = [
                'existing_facilities' => $request->existing_facilities[$i] ?? null,
                'existing_employment' => $request->existing_employment[$i] ?? null,
                'expansion_facilities' => $request->expansion_facilities[$i] ?? null,
                'expansion_employment' => $request->expansion_employment[$i] ?? null,
            ];
        }
    }
    
    // Process investment components
    $investmentComponents = [
        'land' => [
            'estimated' => $request->land_est ?? 0,
            'investment_made' => $request->land_inv ?? 0,
        ],
        'building' => [
            'estimated' => $request->building_est ?? 0,
            'investment_made' => $request->building_inv ?? 0,
        ],
        'machinery' => [
            'estimated' => $request->machinery_est ?? 0,
            'investment_made' => $request->machinery_inv ?? 0,
        ],
        'engineering' => [
            'estimated' => $request->engineering_est ?? 0,
            'investment_made' => $request->engineering_inv ?? 0,
        ],
        'preop' => [
            'estimated' => $request->preop_est ?? 0,
            'investment_made' => $request->preop_inv ?? 0,
        ],
        'margin' => [
            'estimated' => $request->margin_est ?? 0,
            'investment_made' => $request->margin_inv ?? 0,
        ],
    ];
    
    // Process means of finance
    $meansOfFinance = [
        'share_capital' => [
            'promoters' => $request->share_promoters ?? 0,
            'financial_institutions' => $request->share_financial ?? 0,
            'public' => $request->share_public ?? 0,
            'total' => ($request->share_promoters ?? 0) + ($request->share_financial ?? 0) + ($request->share_public ?? 0),
        ],
        'loans' => [
            'financial_institutions' => $request->loan_financial ?? 0,
            'banks' => $request->loan_banks ?? 0,
            'others' => $request->loan_others ?? 0,
            'total' => ($request->loan_financial ?? 0) + ($request->loan_banks ?? 0) + ($request->loan_others ?? 0),
        ],
    ];
    
    // Process signature
    $signaturePath = null;
    if ($request->hasFile('signature')) {
        $file = $request->file('signature');
        if ($file->isValid()) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $safeName = Str::slug($originalName);
            $fileName = $safeName . '_' . time() . '_' . uniqid() . '.' . $extension;
            $signaturePath = $file->storeAs('signatures', $fileName, 'public');
        }
    }
    
    // Process enclosures and other documents
    $enclosures = $this->processEnclosures($request);
    $otherDocuments = $this->processOtherDocuments($request);
    
    // Prepare final registration data
    return [
        'applicant_name' => $request->applicant_name,
        'company_name' => $request->company_name,
        'enterprise_type' => $request->enterprise_type,
        'aadhar_number' => $request->aadhar_number,
        'application_category' => $request->application_category,
        'region_id' => $request->region_id,
        'district_id' => $request->district_id,
        'site_address' => $siteAddress,
        'udyog_aadhar' => $request->udyog_aadhar ?? null,
        'gst_number' => $request->gst_number ?? null,
        'zone' => $request->zone,
        'project_type' => $request->project_type,
        'eligibility_certificate' => $request->project_type === 'New' ? null : $request->eligibility_certificate,
        'expansion_details' => $expansionDetails,
        'entrepreneurs_profile' => $entrepreneurs,
        'project_category' => $request->project_category,
        'other_category' => $request->other_category ?? null,
        'project_subcategory' => $request->project_subcategory,
        'project_description' => $request->project_description,
        'land_area' => $request->land_area,
        'land_ownership_type' => $request->land_ownership_type,
        'building_ownership_type' => $request->building_ownership_type,
        'project_cost' => $request->project_cost,
        'total_employees' => $request->total_employees,
        'investment_components' => $investmentComponents,
        'means_of_finance' => $meansOfFinance,
        'declaration_accepted' => true,
        'place' => $request->place,
        'date' => $request->date,
        'signature_path' => $signaturePath,
        'enclosures' => $enclosures,
        'other_documents' => $otherDocuments,
    ];
}

private function processEnclosures($request)
{
    $enclosures = [];
    $enclosureDocs = [
        'commencement_certificate',
        'sanctioned_plan',
        'proof_of_identity',
        'proof_of_address',
        'land_ownership',
        'project_report',
        'incorporation_documents',
        'gst_registration',
        'special_category_proof',
        'ca_certificate',
        'processing_fee_challan',
    ];
    
    foreach ($enclosureDocs as $doc) {
        $docNo = $request->input($doc . '_doc_no');
        $issueDate = $request->input($doc . '_issue_date');
        $filePath = $this->uploadFile($request, $doc . '_file', $doc);
        
        if ($docNo || $issueDate || $filePath) {
            $enclosures[$doc] = [
                'doc_no' => $docNo,
                'issue_date' => $issueDate,
                'file_path' => $filePath,
            ];
        }
    }
    
    return $enclosures;
}

private function processOtherDocuments($request)
{
    $otherDocuments = [];
    $otherNames = $request->input('other_doc_name', []);
    $otherNos = $request->input('other_doc_no', []);
    $otherIssues = $request->input('other_issue_date', []);
    $otherValid = $request->input('other_validity_date', []);
    
    foreach ($otherNames as $i => $name) {
        $docNo = $otherNos[$i] ?? null;
        $issueDate = $otherIssues[$i] ?? null;
        $validDate = $otherValid[$i] ?? null;
        $filePath = $this->uploadFile($request, 'other_doc_file', 'other_document', $i);
        
        if ($name || $docNo || $issueDate || $validDate || $filePath) {
            $otherDocuments[] = [
                'name' => $name,
                'doc_no' => $docNo,
                'issue_date' => $issueDate,
                'validity_date' => $validDate,
                'file_path' => $filePath,
            ];
        }
    }
    
    return $otherDocuments;
}

private function uploadFile($request, $fieldName, $docName = null, $index = null)
{
    if ($index !== null) {
        $files = $request->file($fieldName);
        if (!is_array($files) || !isset($files[$index])) {
            return null;
        }
        $file = $files[$index];
    } else {
        $file = $request->file($fieldName);
    }
    
    if (!$file instanceof UploadedFile || !$file->isValid()) {
        return null;
    }
    
    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $extension = $file->getClientOriginalExtension();
    $safeOriginal = Str::slug($originalName);
    $safeDocName = $docName ? Str::slug($docName) : 'document';
    $fileName = $safeDocName . '_' . $safeOriginal . '_' . time() . '_' . uniqid() . '.' . $extension;
    $directory = 'ProvisionalRegistration/' . $safeDocName;
    
    return $file->storeAs($directory, $fileName, 'public');
}

protected function generateUniqueRegistrationId($prefix = 'MV')
    {
    do {
        $id = strtoupper($prefix . '-' . Str::upper(Str::random(8)));
    } while (User::where('registration_id', $id)->exists());
    
    return $id;
    }

}