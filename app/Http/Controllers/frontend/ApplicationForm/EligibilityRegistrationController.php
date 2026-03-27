<?php

namespace App\Http\Controllers\frontend\ApplicationForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\frontend\ApplicationForm\EligibilityRegistration;
use Illuminate\Support\Str;
use App\Models\Admin\ApplicationForm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Admin\master\Divisions;
use App\Models\District;
use App\Models\Country;
use App\Models\frontend\Api\ApplicationMovement;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EligibilityRegistrationController extends Controller
{
     public function EligibilityForm()
    {
        
           $application_form = ApplicationForm::where('is_active', 1)
        ->where('slug','issuance-of-eligibility-certificate')
        ->first();
        if(!$application_form){
            return response()->json([
                'status' => false,
                'message' => 'No Available Application Forms.'
            ], 400);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Issuance of Eligibility Certificate Registration Forms.',
                'data' => $application_form,
            ]);
        }
        
    }

    public function store(Request $request)
    {
         if ($request->is('api/*')) {
        $form = ApplicationForm::where('is_active', 1)
            ->where('slug', $request->slug)
            ->first();
             if (!$form) {
            return response()->json([
            'status' => false,
            'message' => 'Invalid Application type provided.'
        ], 400);
        }
        }

        $currentYear = date('Y');
        // return $request->all();
        // dd($request->all());
        try {
            // $validated = $request->validate([
                $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
                'mobile' => 'required|string|max:10|regex:/^[6-9][0-9]{9}$/',

                'applicant_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
                'provisional_number' => ['nullable', 'string', 'max:255'],
                'gst_number' => ['nullable', 'string', 'max:15'],
                'project_description' => ['required', 'string', 'min:10'],
                'region_id' => ['required', 'integer', 'exists:divisions,id'],
                'district_id' => ['required', 'integer', 'exists:districts,id'],
                'commencement_date' => ['nullable', 'date'],
                'operation_details' => ['nullable', 'string', 'max:255'],
                'declaration_place' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
                'declaration_date' => ['required', 'date'],
                'signature_upload' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            ], [
                'applicant_name.regex' => 'Name may contain only letters and spaces.',
                'declaration_place.regex' => 'Place may contain only letters and spaces.',
            ]);

             if ($request->is('api/*')) {
               if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
            }else{
                if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }}

        $validated = $validator->validated();
            DB::beginTransaction();

             if ($request->is('api/*')) {
                $regId = $this->generateUniqueRegistrationId();
                $user = User::create([
                'name' => $request->applicant_name,
                'username' => $request->applicant_name,
                'registration_id' => $regId,
                'image' => null,
                'phone' => $request->mobile ?? null,
                'email' => $request->email ?? null,
                'role' => 'user',
                'status' => 'active',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'is_email_verified' => true,
                'is_phone_verified' => true,
                'is_aadhar_verified' => false,
                'password' => Hash::make($request->mobile),
                'aadhar' => $request->aadhar_no ?? null,
            ]);

              if (!$user) {
                return response()->json([
                'status' => false,
                'message' => 'Failed to create user'
                ], 500);
              }

            }

              if ($request->is('api/*')) {
                $UserID = $user->id;
                $application_form_id = $form->id;
                $is_maitri = 1;
              }else{
                $UserID =  Auth::id();
                $is_maitri = 0;
              }

            // Signature upload
            $signaturePath = null;

            if ($request->hasFile('signature_upload')) {
                $file = $request->file('signature_upload');
                $filename = 'signature_' . time() . '.' . $file->getClientOriginalExtension();
                $signaturePath = $file->storeAs('eligibility/signatures', $filename, 'public');
            }


            // JSON fields
            $entrepreneurs = $request->input('entrepreneurs', []);
            $costComponent = $request->input('cost_component', []);
            $assetAge = $request->input('asset_age', []);
            $ownership = $request->input('ownership', []); // sab ownership[...][] checkboxes bhi isme

            // Enclosures (each key -> doc_no, issue_date, file)
            $enclosures = [];
            if ($request->has('enclosures')) {
                foreach ($request->enclosures as $key => $enc) {
                    $filePath = null;

                    if ($request->hasFile("enclosures.$key.file")) {
                        $file = $request->file("enclosures.$key.file");
                        $label = $enclosure['label'] ?? $key;
                        $safeName = Str::slug($label, '_');

                        $filename = $safeName . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $filePath = $file->storeAs('eligibility/enclosures', $filename, 'public');
                    }
                    $enclosures[$key] = [
                        'label' => $enc['label'] ?? null,
                        'doc_no' => $enc['doc_no'] ?? null,
                        'issue_date' => $enc['issue_date'] ?? null,
                        'file_path' => $filePath,
                    ];
                }
            }

            // Other docs
            $otherDocs = [];
            if ($request->has('other_docs')) {
                foreach ($request->other_docs as $idx => $doc) {
                    if (empty($doc['name']) && empty($doc['doc_no']) && !$request->hasFile("other_docs.$idx.file")) {
                        continue;
                    }

                    $filePath = null;
                    if ($request->hasFile("other_docs.$idx.file")) {
                        $filePath = $request->file("other_docs.$idx.file")
                            ->store('eligibility/other_docs', 'public');
                    }

                    $otherDocs[] = [
                        'name' => $doc['name'] ?? null,
                        'doc_no' => $doc['doc_no'] ?? null,
                        'issue_date' => $doc['issue_date'] ?? null,
                        'validity_date' => $doc['validity_date'] ?? null,
                        'file_path' => $filePath,
                    ];
                }
            }


            $lastRecord = EligibilityRegistration::whereYear('created_at', $currentYear)
            ->whereNotNull('registration_id')
            ->orderBy('id', 'desc')
            ->first();

            $nextNumber = 1;

            if ($lastRecord) {
                preg_match('/EC-(\d+)/', $lastRecord->registration_id, $matches);
                if (isset($matches[1])) {
                    $nextNumber = (int)$matches[1] + 1;
                }
            }

            $ecNumber = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
            $registrationId = "No. DoT/Ince/TP-{$currentYear}/EC-{$ecNumber}/{$currentYear}";
            

            $appFormId = $request->input('application_form_id');
            // $registrationId = 'ELIG-' . strtoupper(Str::random(8));

            // No. DoT/ Ince/ TP-2024/ EC-01/ 2026

            $registration = EligibilityRegistration::create([
                'applicant_name' => $validated['applicant_name'],
                'provisional_number' => $validated['provisional_number'] ?? null,
                'gst_number' => $validated['gst_number'] ?? null,

                'entrepreneurs' => $entrepreneurs,
                'project_description' => $validated['project_description'],

                'commencement_date' => $validated['commencement_date'] ?? null,
                'operation_details' => $validated['operation_details'] ?? null,

                'region_id' => $validated['region_id'] ?? null,
                'district_id' => $validated['district_id'] ?? null,

                'cost_component' => $costComponent,
                'asset_age' => $assetAge,
                'ownership' => $ownership,

                'enclosures' => $enclosures,
                'other_docs' => $otherDocs,

                'signature_path' => $signaturePath,
                'declaration_place' => $validated['declaration_place'],
                'declaration_date' => $validated['declaration_date'],

                'status' => 'submitted',
                'is_apply' => true,
                'submitted_at' => Carbon::now(),

                'user_id' => $UserID,
                'registration_id' => $registrationId,
                'slug_id' => (string) Str::uuid(),
                'application_form_id' => $appFormId ?? $application_form_id,
                'current_stage' => 'Clerk',
                'workflow_status' => 'Pending',
                'is_maitri' => $is_maitri,
            ]);

            ApplicationMovement::updateOrCreate(
                [
                    'application_id' => $registration->registration_id,
                    'desk_number'    => 1,
                ],
                [
                    'officer_name' => 'Clerk',
                    'remarks'       => 'Under review',
                    'action'       => 'Pending',
                    'action_datetime'   => now(),
                ]
            );

            DB::commit();
            if ($request->is('api/*')) {
            return response()->json([
            'status' => true,
            'message' => 'Your registration has been submitted successfully!',
            'application_id' => $registration->registration_id ?? null,
            ]);
        }

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Eligibility Application submitted successfully.',
                    // 'redirect_url' => route('eligibility-registrations.show', $registration->id),
                    'redirect_url' => route('applications.index'),
                ]);
            }

            return redirect()
                ->route('applications.index')
                ->with('success', 'Application submitted successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->is('api/*')) {
            return response()->json([
                    'status' => false,
                    'message' => 'There was an error submitting your registration',
                    'errors' => $e->getMessage()
                ], 422);
            }

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something went wrong. Please try again.',
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }
    protected function generateUniqueRegistrationId($prefix = 'MV')
    {
    do {
        $id = strtoupper($prefix . '-' . Str::upper(Str::random(8)));
    } while (User::where('registration_id', $id)->exists());
    
    return $id;
    }

    public function show(EligibilityRegistration $registration)
    {
        abort_unless($registration->user_id === Auth::id(), 403);
        $application_form = ApplicationForm::find($registration->application_form_id);
        $region = Divisions::find($registration->region_id);
        $district = District::find($registration->district_id);
        return view('frontend.Application.Eligibility.reports', compact(
            'registration',
            'application_form',
            'region',
            'district'
        ));
    }
}
