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
use App\Models\frontend\ApplicationForm\StampDutyApplication;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Admin\master\Category;
use App\Models\Admin\master\Enterprise;
use Carbon\Carbon;

class StampDutyRegistrationApiController extends Controller
{
     public function StampDutyForm()
    {
        
           $application_form = ApplicationForm::where('is_active', 1)
        ->where('slug','issuance-of-no-objection-certificate')
        ->first();
        if(!$application_form){
            return response()->json([
                'status' => false,
                'message' => 'No Available Application Forms.'
            ], 400);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Issuance of No Objection Registration Certificate Forms.',
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
                'status'  => false,
                'message' => 'Invalid Application type provided.'
            ], 400);
        }
 
        // Validate all data at once
        $validator = Validator::make($request->all(), $this->getCombinedValidationRules(), $this->messages());
 
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }
 
        $validated = $validator->validated();
 
        DB::beginTransaction();
 
        try {
            // Create user
            $regId = $this->generateUniqueRegistrationId();
            $user  = User::create([
                'name'               => $request->applicant_name,
                'username'           => $request->applicant_name,
                'registration_id'    => $regId,
                'image'              => null,
                'phone'              => $request->phone ?? null,
                'email'              => $request->email ?? null,
                'role'               => 'user',
                'status'             => 'active',
                'email_verified_at'  => now(),
                'phone_verified_at'  => now(),
                'is_email_verified'  => true,
                'is_phone_verified'  => true,
                'is_aadhar_verified' => false,
                'password'           => Hash::make($request->phone),
                'aadhar'             => $request->aadhar_no ?? null,
            ]);
 
            if (!$user) {
                throw new \Exception('Failed to create user');
            }
 
            $userId= $user->id;
            $application_form_id = $form->id;
 
            // Create registration
            $registration = StampDutyApplication::create([
                'application_form_id' => $application_form_id,
                'user_id'             => $userId,
                'registration_id'     => 'STD-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
                'slug_id'             => 'STD-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
                'submitted_at'        => now(),
                'current_step'        => 6,
                'progress'            => ['done' => 6, 'total' => 6],
                'is_apply'            => true,
                'is_maitri'           => true,
                'is_completed'        => true,
                'status'              => true,
            ]);
 
            if (!$registration) {
                throw new \Exception('Failed to create registration. Please try again.');
            }
 
            // Fill all step data (files + fields)
            $this->fillStepData($registration, $validated, $request);
 
            // Update application status
            $registration->declaration_accepted = true;
            $registration->status               = 'submitted';
            $registration->is_completed         = true;
            $registration->current_stage        = 'Clerk';
            $registration->workflow_status      = 'Pending';
            $registration->save();
 
            // Create application movement record
            ApplicationMovement::updateOrCreate(
                [
                    'application_id' => $registration->registration_id,
                    'desk_number'    => 1,
                ],
                [
                    'officer_name'    => 'Clerk',
                    'remarks'         => 'Under review',
                    'action'          => 'Pending',
                    'action_datetime' => now(),
                ]
            );
 
            DB::commit();
 
            return response()->json([
                'status'  => true,
                'message' => 'Application submitted successfully',
                'data'    => [
                    'registration_id' => $registration->registration_id,
                ]
            ], 201);
 
        } catch (\Exception $e) {
            DB::rollBack();
 
            return response()->json([
                'status'  => false,
                'message' => 'Failed to submit application',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
 
    // ─────────────────────────────────────────────
    // Validation Rules
    // ─────────────────────────────────────────────
    private function getCombinedValidationRules(): array
    {
        $fileRule = 'required'; // Change to 'nullable' if files are optional
 
        return [
            // User / General Details
            'email'            => 'required|email|unique:users,email',
            'phone'            => 'required|string|max:10|regex:/^[6-9][0-9]{9}$/',
            'applicant_name'   => 'required|string|max:255',
            'region_id'        => 'required',
            'district_id'      => 'required',
            'company_name'     => ['required', 'regex:/^[A-Za-z0-9\s\.,&\'-]+$/'],
            'registration_no'  => 'required|string|max:191',
            'application_date' => 'required|date',
            'applicant_type'   => 'required|string',
            'agreement_type'   => 'required|string',
 
            // Communication Address
            'c_address' => 'required|string',
            'c_city'    => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'c_taluka'  => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'c_district'=> ['required', 'regex:/^[A-Za-z\s]+$/'],
            'c_state'   => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'c_pincode' => ['required', 'regex:/^[1-9][0-9]{5}$/'],
            'c_mobile'  => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'c_email'   => 'required|email',
 
            // Permanent Address
            'p_address' => 'required|string',
            'p_city'    => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'p_taluka'  => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'p_district'=> ['required', 'regex:/^[A-Za-z\s]+$/'],
            'p_state'   => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'p_pincode' => ['required', 'regex:/^[1-9][0-9]{5}$/'],
            'p_mobile'  => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'p_email'   => 'required|email',
 
            // Land Details
            'land_gat'      => 'required|string',
            'land_village'  => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'land_taluka'   => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'land_district' => ['required', 'regex:/^[A-Za-z\s]+$/'],
            'area_a'        => 'required|numeric|min:0',
            'area_b'        => 'required|numeric|min:0',
            'area_c'        => 'required|numeric|min:0',
            'area_d'        => 'required|numeric|min:0',
            'area_e'        => 'required|numeric|min:0',
            'na_area'       => 'nullable|numeric|min:0',
 
            // Investment Details
            'estimated_project_cost' => 'required|numeric|min:0',
            'proposed_employment'    => 'required|integer|min:0',
            'tourism_activities'     => 'required|string',
            'incentives_availed'     => 'nullable|string',
            'existed_before'         => 'required|boolean',
            'eligibility_cert_no'    => 'required_if:existed_before,1|nullable|string',
            'eligibility_date'       => 'required_if:existed_before,1|nullable|date',
            'present_status'         => 'required_if:existed_before,1|nullable|string',
            'cost_land'              => 'required|numeric|min:0',
            'cost_building'          => 'required|numeric|min:0',
            'cost_machinery'         => 'required|numeric|min:0',
            'cost_electrical'        => 'required|numeric|min:0',
            'cost_misc'              => 'required|numeric|min:0',
            'cost_other'             => 'required|numeric|min:0',
            'project_employment'     => 'required|integer|min:0',
            'noc_purpose'            => 'required|string',
            'noc_authority'          => 'required|string',
 
            // Documents
            // 'doc_challan'           => [$fileRule, 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
            // 'doc_affidavit'         => [$fileRule, 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
            // 'doc_registration'      => [$fileRule, 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
            // 'doc_ror'               => [$fileRule, 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
            // 'doc_land_map'          => [$fileRule, 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
            // 'doc_dpr'               => [$fileRule, 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
            // 'doc_agreement'         => [$fileRule, 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
            // 'doc_construction_plan' => [$fileRule, 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
            // 'doc_dp_remarks'        => [$fileRule, 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
 
            // Declaration
            'name_designation'   => ['required', 'regex:/^[A-Za-z\s\.]+$/'],
            // 'signature'          => [$fileRule, 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
            // 'stamp'              => ['nullable', 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
            'declaration_accept' => 'required|accepted',
        ];
    }
 
    // ─────────────────────────────────────────────
    // Validation Messages
    // ─────────────────────────────────────────────
    protected function messages(): array
    {
        return [
            'company_name.regex'      => 'Enter valid company name (letters, numbers, spaces, .,&,-)',
            'c_city.regex'            => 'Only letters and spaces allowed',
            'c_taluka.regex'          => 'Only letters and spaces allowed',
            'c_district.regex'        => 'Only letters and spaces allowed',
            'c_state.regex'           => 'Only letters and spaces allowed',
            'c_pincode.regex'         => 'Enter 6-digit pin code',
            'c_mobile.regex'          => 'Enter valid 10-digit mobile number starting with 6-9',
            'p_city.regex'            => 'Only letters and spaces allowed',
            'p_taluka.regex'          => 'Only letters and spaces allowed',
            'p_district.regex'        => 'Only letters and spaces allowed',
            'p_state.regex'           => 'Only letters and spaces allowed',
            'p_pincode.regex'         => 'Enter 6-digit pin code',
            'p_mobile.regex'          => 'Enter valid 10-digit mobile number starting with 6-9',
            'land_village.regex'      => 'Only letters and spaces allowed',
            'land_taluka.regex'       => 'Only letters and spaces allowed',
            'land_district.regex'     => 'Only letters and spaces allowed',
            'name_designation.regex'  => 'Only letters, spaces and dots allowed',
            'aff_name.regex'          => 'Only letters, spaces and dots allowed',
            'aff_village.regex'       => 'Only letters and spaces allowed',
            'aff_taluka.regex'        => 'Only letters and spaces allowed',
            'aff_district.regex'      => 'Only letters and spaces allowed',
        ];
    }
 
    // ─────────────────────────────────────────────
    // Fill Step Data (Fields + Files)
    // ─────────────────────────────────────────────
    protected function fillStepData(StampDutyApplication $app, array $validated, Request $request): void
    {
        // Step 1 - General Details
        $app->region_id        = $validated['region_id'];
        $app->district_id      = $validated['district_id'];
        $app->company_name     = $validated['company_name'];
        $app->registration_no  = $validated['registration_no'];
        $app->application_date = $validated['application_date'];
        $app->applicant_type   = $validated['applicant_type'];
        $app->agreement_type   = $validated['agreement_type'];
 
        // Step 2 - Address Details
        $app->c_address = $validated['c_address'];
        $app->c_city    = $validated['c_city'];
        $app->c_taluka  = $validated['c_taluka'];
        $app->c_district= $validated['c_district'];
        $app->c_state   = $validated['c_state'];
        $app->c_pincode = $validated['c_pincode'];
        $app->c_mobile  = $validated['c_mobile'];
        $app->c_phone   = $request->input('c_phone');
        $app->c_email   = $validated['c_email'];
        $app->c_fax     = $request->input('c_fax');
 
        $app->p_address = $validated['p_address'];
        $app->p_city    = $validated['p_city'];
        $app->p_taluka  = $validated['p_taluka'];
        $app->p_district= $validated['p_district'];
        $app->p_state   = $validated['p_state'];
        $app->p_pincode = $validated['p_pincode'];
        $app->p_mobile  = $validated['p_mobile'];
        $app->p_phone   = $request->input('p_phone');
        $app->p_email   = $validated['p_email'];
        $app->p_website = $request->input('p_website');
 
        // Step 3 - Land Details
        $app->land_gat      = $validated['land_gat'];
        $app->land_village  = $validated['land_village'];
        $app->land_taluka   = $validated['land_taluka'];
        $app->land_district = $validated['land_district'];
        $app->area_a        = $validated['area_a'];
        $app->area_b        = $validated['area_b'];
        $app->area_c        = $validated['area_c'];
        $app->area_d        = $validated['area_d'];
        $app->area_e        = $validated['area_e'];
 
        $app->na_gat      = $request->input('na_gat');
        $app->na_village  = $request->input('na_village');
        $app->na_taluka   = $request->input('na_taluka');
        $app->na_district = $request->input('na_district');
        $app->na_area     = $request->input('na_area');
 
        // Step 4 - Investment Details
        $app->estimated_project_cost = $validated['estimated_project_cost'];
        $app->proposed_employment    = $validated['proposed_employment'];
        $app->tourism_activities     = $validated['tourism_activities'];
        $app->incentives_availed     = $request->input('incentives_availed');
        $app->existed_before         = $validated['existed_before'];
        $app->eligibility_cert_no    = $request->input('eligibility_cert_no');
        $app->eligibility_date       = $request->input('eligibility_date');
        $app->present_status         = $request->input('present_status');
        $app->cost_land              = $validated['cost_land'];
        $app->cost_building          = $validated['cost_building'];
        $app->cost_machinery         = $validated['cost_machinery'];
        $app->cost_electrical        = $validated['cost_electrical'];
        $app->cost_misc              = $validated['cost_misc'];
        $app->cost_other             = $validated['cost_other'];
        $app->project_employment     = $validated['project_employment'];
        $app->noc_purpose            = $validated['noc_purpose'];
        $app->noc_authority          = $validated['noc_authority'];
 
        // Step 5 - Document Files
        $fileFields = [
            'doc_challan',
            'doc_affidavit',
            'doc_registration',
            'doc_ror',
            'doc_land_map',
            'doc_dpr',
            'doc_agreement',
            'doc_construction_plan',
            'doc_dp_remarks',
        ];
 
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
 
                // Delete old file if exists
                if (!empty($app->{$field}) && Storage::disk('public')->exists($app->{$field})) {
                    Storage::disk('public')->delete($app->{$field});
                }
 
                $filename  = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $path      = $file->storeAs("stamp-duty/{$field}", $filename, 'public');
                $app->{$field} = $path;
            }
        }
 
        // Step 6 - Declaration
        $app->name_designation = $validated['name_designation'];
 
        if ($request->hasFile('signature')) {
            if (!empty($app->signature_path) && Storage::disk('public')->exists($app->signature_path)) {
                Storage::disk('public')->delete($app->signature_path);
            }
            $signatureFile    = $request->file('signature');
            $extension        = $signatureFile->getClientOriginalExtension();
            $filename         = time() . '_signature.' . $extension;
            $path             = $signatureFile->storeAs('stamp-duty/signatures', $filename, 'public');
            $app->signature_path = $path;
        }
 
        if ($request->hasFile('stamp')) {
            if (!empty($app->stamp_path) && Storage::disk('public')->exists($app->stamp_path)) {
                Storage::disk('public')->delete($app->stamp_path);
            }
            $stampFile    = $request->file('stamp');
            $extension    = $stampFile->getClientOriginalExtension();
            $filename     = time() . '_stamp.' . $extension;
            $path         = $stampFile->storeAs('stamp-duty/stamps', $filename, 'public');
            $app->stamp_path = $path;
        }
 
        $app->save();
    }
 
    // ─────────────────────────────────────────────
    // Generate Unique Registration ID
    // ─────────────────────────────────────────────
    protected function generateUniqueRegistrationId(string $prefix = 'MV'): string
    {
        do {
            $id = strtoupper($prefix . '-' . Str::upper(Str::random(8)));
        } while (User::where('registration_id', $id)->exists());
 
        return $id;
    }
}