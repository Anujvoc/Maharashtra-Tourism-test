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
            'email' => $request->email ?? null,
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

         $registration = StampDutyApplication::firstOrCreate(
            [
                'application_form_id' => $application_form_id,
                'user_id' => $userId,
                'registration_id' => 'STD-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
                'slug_id' => 'STD-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
                'submitted_at' => now(),
            ],
            array_merge($registrationData, [
                'current_step' => 6,
                'progress' => 6,
                'progress' => ['done' => 6, 'total' => 6],
                'is_apply' => true,
                'is_maitri' => true,
                'is_completed' => true,
                'status' => true,
            ])
        );

        if (!$registration) {
         return response()->json([
        'status' => false,
        'message' => 'Failed to create registration. Please try again.'
        ], 400);
    }
        
        // Process all data at once
        $registrationData = $this->fillStepData($request);
        $application = StampDutyApplication::where('user_id', Auth::id())
            ->findOrFail($registration->id);
        $application->declaration_accepted = true;
        $application->status = 'submitted';
        $application->is_completed = true;
        $application->current_stage = 'Clerk';
        $application->workflow_status = 'Pending';
        $application->save();
        
        
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
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:10|regex:/^[6-9][0-9]{9}$/',
        'applicant_name' => 'required|string|max:255',
        'region_id' => ['required'],
        'district_id' => ['required'],
        'company_name' => ['required', 'regex:/^[A-Za-z0-9\s\.,&\'-]+$/'],
        'registration_no' => ['required', 'string', 'max:191'],
        'application_date' => ['required', 'date'],
        'applicant_type' => ['required', 'string'],
        'agreement_type' => ['required', 'string'],
        
        // Location/Site Details
        'c_address' => ['required', 'string'],
          'c_city' => ['required', 'regex:/^[A-Za-z\s]+$/'],
         'c_taluka' => ['required', 'regex:/^[A-Za-z\s]+$/'],
         'c_district' => ['required', 'regex:/^[A-Za-z\s]+$/'],
        'c_state' => ['required', 'regex:/^[A-Za-z\s]+$/'],
        'c_pincode' => ['required', 'regex:/^[1-9][0-9]{5}$/'],
        'c_mobile' => ['required', 'regex:/^[6-9][0-9]{9}$/'],
        'c_email' => ['required', 'email'],
        'p_address' => ['required', 'string'],
        'p_city' => ['required', 'regex:/^[A-Za-z\s]+$/'],
        'p_taluka' => ['required', 'regex:/^[A-Za-z\s]+$/'],
        'p_district' => ['required', 'regex:/^[A-Za-z\s]+$/'],
        'p_state' => ['required', 'regex:/^[A-Za-z\s]+$/'],
        'p_pincode' => ['required', 'regex:/^[1-9][0-9]{5}$/'],
        'p_mobile' => ['required', 'regex:/^[6-9][0-9]{9}$/'],
        'p_email' => ['required', 'email'],
        
        'land_gat' => ['required', 'string'],
        'land_village' => ['required', 'regex:/^[A-Za-z\s]+$/'],
        'land_taluka' => ['required', 'regex:/^[A-Za-z\s]+$/'],
        'land_district' => ['required', 'regex:/^[A-Za-z\s]+$/'],
        'area_a' => ['required', 'numeric', 'min:0'],
        'area_b' => ['required', 'numeric', 'min:0'],
        'area_c' => ['required', 'numeric', 'min:0'],
        'area_d' => ['required', 'numeric', 'min:0'],
        'area_e' => ['required', 'numeric', 'min:0'],
        'na_area' => ['nullable', 'numeric', 'min:0'],
        
        // Investment Details
        'estimated_project_cost' => ['required', 'numeric', 'min:0'],
        'proposed_employment' => ['required', 'integer', 'min:0'],
        'tourism_activities' => ['required', 'string'],
        'incentives_availed' => ['nullable', 'string'],
        'existed_before' => ['required', 'boolean'],
        'eligibility_cert_no' => ['required_if:existed_before,1', 'nullable', 'string'],
        'eligibility_date' => ['required_if:existed_before,1', 'nullable', 'date'],
        'present_status' => ['required_if:existed_before,1', 'nullable', 'string'],
        'cost_land' => ['required', 'numeric', 'min:0'],
        'cost_building' => ['required', 'numeric', 'min:0'],
        'cost_machinery' => ['required', 'numeric', 'min:0'],
        'cost_electrical' => ['required', 'numeric', 'min:0'],
        'cost_misc' => ['required', 'numeric', 'min:0'],
        'cost_other' => ['required', 'numeric', 'min:0'],
        'project_employment' => ['required', 'integer', 'min:0'],
        'noc_purpose' => ['required', 'string'],
        'noc_authority' => ['required', 'string'],
        
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
                 [$fileFields] = [
                       'nullable' : 'required',
                        'file',
                        'max:2048',
                        'mimes:jpg,jpeg,png,pdf',
                    ];

                      'name_designation' => ['required', 'regex:/^[A-Za-z\s\.]+$/'],
                    'signature' => [
                      'nullable' : 'required',
                        'file',
                        'max:2048',
                        'mimes:jpg,jpeg,png,pdf',
                    ],

                    'stamp' => [
                        'nullable',
                        'file',
                        'max:2048',
                        'mimes:jpg,jpeg,png,pdf',
                    ],

                     'declaration_accept' => ['required', 'accepted'],
    ];
}

protected function messages(): array
    {
        return [
            'company_name.regex' => 'Enter valid company name (letters, numbers, spaces, .,&,-)',
            'c_city.regex' => 'Only letters and spaces allowed',
            'c_taluka.regex' => 'Only letters and spaces allowed',
            'c_district.regex' => 'Only letters and spaces allowed',
            'c_state.regex' => 'Only letters and spaces allowed',
            'c_pincode.regex' => 'Enter 6-digit pin code',
            'c_mobile.regex' => 'Enter valid 10-digit mobile number starting with 6-9',
            'p_city.regex' => 'Only letters and spaces allowed',
            'p_taluka.regex' => 'Only letters and spaces allowed',
            'p_district.regex' => 'Only letters and spaces allowed',
            'p_state.regex' => 'Only letters and spaces allowed',
            'p_pincode.regex' => 'Enter 6-digit pin code',
            'p_mobile.regex' => 'Enter valid 10-digit mobile number starting with 6-9',
            'land_village.regex' => 'Only letters and spaces allowed',
            'land_taluka.regex' => 'Only letters and spaces allowed',
            'land_district.regex' => 'Only letters and spaces allowed',
            'name_designation.regex' => 'Only letters, spaces and dots allowed',
            'aff_name.regex' => 'Only letters, spaces and dots allowed',
            'aff_village.regex' => 'Only letters and spaces allowed',
            'aff_taluka.regex' => 'Only letters and spaces allowed',
            'aff_district.regex' => 'Only letters and spaces allowed',
        ];
    }

    protected function fillStepData(StampDutyApplication $app, array $validated, Request $request): void
    {
        
               
                    'region_id' => $validated['region_id'],
                    'district_id' => $validated['district_id'],
                    'company_name' => $validated['company_name'],
                    'registration_no' => $validated['registration_no'],
                    'application_date' => $validated['application_date'],
                    'applicant_type' => $validated['applicant_type'],
                    'agreement_type' => $validated['agreement_type'],
               

          
                    'c_address' => $validated['c_address'],
                    'c_city' => $validated['c_city'],
                    'c_taluka' => $validated['c_taluka'],
                    'c_district' => $validated['c_district'],
                    'c_state' => $validated['c_state'],
                    'c_pincode' => $validated['c_pincode'],
                    'c_mobile' => $validated['c_mobile'],
                    'c_phone' => $request->input('c_phone'),
                    'c_email' => $validated['c_email'],
                    'c_fax' => $request->input('c_fax'),

                    'p_address' => $validated['p_address'],
                    'p_city' => $validated['p_city'],
                    'p_taluka' => $validated['p_taluka'],
                    'p_district' => $validated['p_district'],
                    'p_state' => $validated['p_state'],
                    'p_pincode' => $validated['p_pincode'],
                    'p_mobile' => $validated['p_mobile'],
                    'p_phone' => $request->input('p_phone'),
                    'p_email' => $validated['p_email'],
                    'p_website' => $request->input('p_website'),
                

            
                    'land_gat' => $validated['land_gat'],
                    'land_village' => $validated['land_village'],
                    'land_taluka' => $validated['land_taluka'],
                    'land_district' => $validated['land_district'],
                    'area_a' => $validated['area_a'],
                    'area_b' => $validated['area_b'],
                    'area_c' => $validated['area_c'],
                    'area_d' => $validated['area_d'],
                    'area_e' => $validated['area_e'],

                    'na_gat' => $request->input('na_gat'),
                    'na_village' => $request->input('na_village'),
                    'na_taluka' => $request->input('na_taluka'),
                    'na_district' => $request->input('na_district'),
                    'na_area' => $request->input('na_area'),
              

                    'estimated_project_cost' => $validated['estimated_project_cost'],
                    'proposed_employment' => $validated['proposed_employment'],
                    'tourism_activities' => $validated['tourism_activities'],
                    'incentives_availed' => $request->input('incentives_availed'),
                    'existed_before' => $validated['existed_before'],
                    'eligibility_cert_no' => $request->input('eligibility_cert_no'),
                    'eligibility_date' => $request->input('eligibility_date'),
                    'present_status' => $request->input('present_status'),

                    'cost_land' => $validated['cost_land'],
                    'cost_building' => $validated['cost_building'],
                    'cost_machinery' => $validated['cost_machinery'],
                    'cost_electrical' => $validated['cost_electrical'],
                    'cost_misc' => $validated['cost_misc'],
                    'cost_other' => $validated['cost_other'],
                    'project_employment' => $validated['project_employment'],
                    'noc_purpose' => $validated['noc_purpose'],
                    'noc_authority' => $validated['noc_authority'],
               
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
                        if (!empty($app->{$field}) && Storage::disk('public')->exists($app->{$field})) {
                            Storage::disk('public')->delete($app->{$field});
                        }
                        $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();

                        $path = $file->storeAs(
                            "stamp-duty/{$field}",
                            $filename,
                            'public'
                        );
                        $app->{$field} = $path;
                    }
                }
             

                'name_designation' => $validated['name_designation'],
                   

                if ($request->hasFile('signature')) {
                    if (!empty($app->signature_path) && Storage::disk('public')->exists($app->signature_path)) {
                        Storage::disk('public')->delete($app->signature_path);
                    }
                    $signatureFile = $request->file('signature');
                    $originalName = $signatureFile->getClientOriginalName();
                    $extension = $signatureFile->getClientOriginalExtension();
                    $filename = time() . '_signature.' . $extension;
                    $path = $signatureFile->storeAs('stamp-duty/signatures', $filename, 'public');
                    $app->signature_path = $path;
                }

                if ($request->hasFile('stamp')) {
                    if (!empty($app->stamp_path) && Storage::disk('public')->exists($app->stamp_path)) {
                        Storage::disk('public')->delete($app->stamp_path);
                    }
                    $stampFile = $request->file('stamp');
                    $extension = $stampFile->getClientOriginalExtension();
                    $filename = time() . '_stamp.' . $extension;
                    $path = $stampFile->storeAs('stamp-duty/stamps', $filename, 'public');
                    $app->stamp_path = $path;
                }
               
        }
    

        protected function generateUniqueRegistrationId($prefix = 'MV')
        {
            do {
                $id = strtoupper($prefix . '-' . Str::upper(Str::random(8)));
                } while (User::where('registration_id', $id)->exists());
                
                return $id;
                }
                private function prepareRegistrationData($request)
                {}
}