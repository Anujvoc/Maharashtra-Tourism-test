<?php

namespace App\Http\Controllers\frontend\ApplicationForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\frontend\ApplicationForm\Application;
use App\Models\frontend\ApplicationForm\ApplicantDetail;
use App\Models\frontend\ApplicationForm\PropertyDetail;
use App\Models\frontend\ApplicationForm\Accommodation;
use App\Models\frontend\ApplicationForm\Facility;
use App\Models\frontend\ApplicationForm\PhotosSignature;
use App\Models\frontend\ApplicationForm\ProvisionalRegistration;
use App\Models\frontend\ApplicationForm\Enclosure;
use App\Models\frontend\ApplicationForm\Document;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\ApplicationForm;
use App\Models\District;
use App\Models\Country;
use App\Models\State;
use App\Models\Admin\master\Enterprise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\Master\Category;
use App\Models\Admin\Master\Divisions;

class ProvisionalRegistrationController extends Controller
{
    public function show(Application $application, $step)
    {

        $registration = ProvisionalRegistration::firstOrCreate(
            [
                'application_id' => $application->id,
                'user_id' => Auth::id()
            ],
            [
                'current_step' => 1,
                'progress' => ['done' => 0, 'total' => 6]
            ]
        );

        // Update application current step
        $application->current_step = $step;
        $application->save();
        $applicantTypes = Enterprise::select('id', 'name')->get();
        $regions      = DB::table('divisions')->select('id', 'name')->get();
        $districts    = DB::table('districts')->where('state_id', 14)->select('id', 'name')->get();
        $states    = DB::table('states')->where('id', 14)->select('id', 'name')->get();
        $categories   = Category::all();
        $application_form = ApplicationForm::where('id', $registration->application_form_id)
            ->firstOrFail();
        // Determine which view to show based on step
        $view = 'frontend.Application.provisional.step' . $step;

        return view($view, [
            'application' => $application,
            'registration' => $registration,
            'applicantTypes' => $applicantTypes,
            'categories' => $categories,
            'regions' => $regions,
            'districts' => $districts,
            'states' => $states,
            'application_form' => $application_form,
            'step' => $step
        ]);
    }
    public function saveStep(Request $request, Application $application, $step)
    {
        $registration = ProvisionalRegistration::where('application_id', $application->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Validate based on step
        $validationRules = $this->getValidationRules($step);
        $validated = $request->validate($validationRules);

        // Process and save data based on step
        $this->processStepData($registration, $step, $validated, $request);

        // Update progress
        $registration->updateProgress($step);

        // If this is the last step, mark as completed
        if ($step == 6) {
            $registration->markAsCompleted();
            return redirect()->route('applications.index')
                ->with('success', 'Application submitted successfully!');
        }

        // Redirect to next step
        return redirect()->route('provisional.wizard.show', [
            'application' => $application,
            'step' => $step + 1
        ])->with('success', 'Step ' . $step . ' saved successfully!');
    }

    private function getValidationRules($step)
    {
        $rules = [];

        switch ($step) {
            case 1: // General Details
                $rules = [
                    'applicant_name' => 'required|string|max:255',
                    'company_name' => 'required|string|max:255',
                    'enterprise_type' => 'required|string',
                    'aadhar_number' => 'required|digits:12',
                    'application_category' => 'required|string',
                    'region_id' => 'required|exists:divisions,id',
                    'district_id' => 'required|exists:districts,id',
                ];
                break;

            case 2:
                $rules = [
                    'survey_type' => 'required|string',
                    'survey_number' => 'required|string',
                    'village_city' => 'required|string|max:255',
                    'taluka' => 'required|string|max:255',
                    'district' => 'required|string|max:255',
                    'state' => 'required|string|max:255',
                    'pincode' => 'required|digits:6',
                    'mobile' => 'required|digits:10',
                    'email' => 'required|email',
                    'website' => 'nullable|url',
                    'udyog_aadhar' => 'nullable|string',
                    'gst_number' => 'nullable|string',
                    'zone' => 'required|string',
                    'project_type' => 'required|string|in:New,Expansion',
                    'project_category' => 'required|string',
                    'project_subcategory' => 'required|string|max:255',
                    'project_description' => 'required|string|min:10',
                ];
                break;

            case 3: // Investment Details
                $rules = [
                    'land_area' => 'required|numeric|min:1',
                    'land_ownership_type' => 'required|string|in:Owned,Leased,Rent',
                    'building_ownership_type' => 'required|string|in:Owned,Leased,Rent',
                    'project_cost' => 'required|numeric|min:0',
                    'total_employees' => 'required|integer|min:1',
                ];
                break;

            case 6: // Declaration
                $rules = [
                    'declaration' => 'required|accepted',
                    'place' => 'required|string|max:255',
                    'date' => 'required|date',
                    'signature' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                ];
                break;
        }

        return $rules;
    }

    private function processStepData($registration, $step, $data, $request)
    {
        switch ($step) {
            case 1:
                $registration->update([
                    'applicant_name' => $data['applicant_name'],
                    'company_name' => $data['company_name'],
                    'enterprise_type' => $data['enterprise_type'],
                    'aadhar_number' => $data['aadhar_number'],
                    'application_category' => $data['application_category'],
                    'region_id' => $data['region_id'],
                    'district_id' => $data['district_id'],
                ]);
                break;

            case 2:
                // Process site address as JSON
                $siteAddress = [
                    'survey_type' => $data['survey_type'],
                    'survey_number' => $data['survey_number'],
                    'village_city' => $data['village_city'],
                    'taluka' => $data['taluka'],
                    'district' => $data['district'],
                    'state' => $data['state'],
                    'pincode' => $data['pincode'],
                    'mobile' => $data['mobile'],
                    'email' => $data['email'],
                    'website' => $data['website'] ?? null,
                ];

                // Process entrepreneurs profile table data
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

                // Process expansion details if project type is Expansion
                $expansionDetails = null;
                if ($data['project_type'] === 'Expansion' && $request->has('existing_facilities')) {
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

                $registration->update([
                    'site_address' => $siteAddress,
                    'udyog_aadhar' => $data['udyog_aadhar'] ?? null,
                    'gst_number' => $data['gst_number'] ?? null,
                    'zone' => $data['zone'],
                    'project_type' => $data['project_type'],
                    'expansion_details' => $expansionDetails,
                    'entrepreneurs_profile' => $entrepreneurs,
                    'project_category' => $data['project_category'],
                    'other_category' => $data['other_category'] ?? null,
                    'project_subcategory' => $data['project_subcategory'],
                    'project_description' => $data['project_description'],
                ]);
                break;

            case 3:
                // Process investment components table
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

                $registration->update([
                    'land_area' => $data['land_area'],
                    'land_ownership_type' => $data['land_ownership_type'],
                    'building_ownership_type' => $data['building_ownership_type'],
                    'project_cost' => $data['project_cost'],
                    'total_employees' => $data['total_employees'],
                    'investment_components' => $investmentComponents,
                ]);
                break;

            case 4: // Means of Finance
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

                $registration->update([
                    'means_of_finance' => $meansOfFinance,
                ]);
                break;



            case 5: // Enclosures

                // ---------- 1. OLD DATA (DB se) ----------
                $oldEnclosures     = $registration->enclosures ?? [];
                $oldOtherDocuments = $registration->other_documents ?? [];

                // ---------- 2. ENCLOSURES ----------
                $enclosures   = [];
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

                    $oldItem = $oldEnclosures[$doc] ?? [];

                    $docNo     = $request->input($doc . '_doc_no');
                    $issueDate = $request->input($doc . '_issue_date');

                    // Blade: name="remove_existing_enclosures[{{$key}}]"
                    $removeExisting = $request->input("remove_existing_enclosures.$doc") == '1';

                    // New upload (agar kuch select kiya ho)
                    $newFilePath = $this->uploadFile($request, $doc . '_file');

                    // Default: purana file_path
                    $finalPath = $oldItem['file_path'] ?? null;

                    // 1) Agar remove flag ON hai → purana delete + null
                    if ($removeExisting && $finalPath) {
                        Storage::disk('public')->delete($finalPath);
                        $finalPath = null;
                    }

                    // 2) Agar naya file upload hua hai → use overwrite karo
                    if ($newFilePath) {
                        // optionally purana remove bhi kar sakte ho
                        if (!empty($oldItem['file_path']) && $oldItem['file_path'] !== $newFilePath) {
                            Storage::disk('public')->delete($oldItem['file_path']);
                        }
                        $finalPath = $newFilePath;
                    }

                    // Agar koi bhi meaningful data hai tabhi array me store karo
                    if ($docNo || $issueDate || $finalPath) {
                        $enclosures[$doc] = [
                            'doc_no'     => $docNo,
                            'issue_date' => $issueDate,
                            'file_path'  => $finalPath,
                        ];
                    }
                }

                // ---------- 3. OTHER DOCUMENTS ----------
                $otherDocuments = [];

                $otherNames  = $request->input('other_doc_name', []);
                $otherNos    = $request->input('other_doc_no', []);
                $otherIssues = $request->input('other_issue_date', []);
                $otherValid  = $request->input('other_validity_date', []);
                $otherRemove = $request->input('other_remove_existing', []); // hidden flags from Blade

                foreach ($otherNames as $i => $name) {

                    $oldItem = $oldOtherDocuments[$i] ?? [];

                    $docNo     = $otherNos[$i]    ?? null;
                    $issueDate = $otherIssues[$i] ?? null;
                    $validDate = $otherValid[$i]  ?? null;

                    $removeExisting = isset($otherRemove[$i]) && $otherRemove[$i] == '1';

                    $newFilePath = $this->uploadFile($request, 'other_doc_file', $i);

                    $finalPath = $oldItem['file_path'] ?? null;

                    // Remove flag ON → delete old file
                    if ($removeExisting && $finalPath) {
                        Storage::disk('public')->delete($finalPath);
                        $finalPath = null;
                    }

                    // New file uploaded
                    if ($newFilePath) {
                        if (!empty($oldItem['file_path']) && $oldItem['file_path'] !== $newFilePath) {
                            Storage::disk('public')->delete($oldItem['file_path']);
                        }
                        $finalPath = $newFilePath;
                    }

                    // Row ko sirf tab store karo jab kuch real data ho
                    if ($name || $docNo || $issueDate || $validDate || $finalPath) {
                        $otherDocuments[] = [
                            'name'         => $name,
                            'doc_no'       => $docNo,
                            'issue_date'   => $issueDate,
                            'validity_date'=> $validDate,
                            'file_path'    => $finalPath,
                        ];
                    }
                }

                // ---------- 4. SAVE ----------
                $registration->update([
                    'enclosures'      => $enclosures,
                    'other_documents' => $otherDocuments,
                ]);

                break;


            case 6: // Declaration
                $signaturePath = null;
                if ($request->hasFile('signature')) {
                    $file = $request->file('signature');

                    if ($file->isValid()) {
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension    = $file->getClientOriginalExtension();
                        $safeName     = Str::slug($originalName);
                        $directory = 'signatures';

                        $fileName      = $safeName . '_' . time() . '_' . uniqid() . '.' . $extension;
                        $signaturePath = $file->storeAs($directory, $fileName, 'public');
                    }
                }

                $registration->update([
                    'declaration_accepted' => true,
                    'place' => $data['place'],
                    'date' => $data['date'],
                    'signature_path' => $signaturePath,
                ]);
                break;
        }
    }
    private function uploadFile($request, $fieldName, $index = null)
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

        if (!$file || !$file->isValid()) {
            return null;
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = $file->getClientOriginalExtension();
        $safeName     = Str::slug($originalName);

        $fileName  = $safeName . '_' . time() . '_' . uniqid() . '.' . $extension;
        $directory = 'documents/' . Auth::id();

        return $file->storeAs($directory, $fileName, 'public');
    }
    private function uploadFile1($request, $fieldName, $index = null)
    {
        if ($index !== null) {
            $file = $request->file($fieldName)[$index] ?? null;
        } else {
            $file = $request->file($fieldName);
        }

        if ($file && $file->isValid()) {
            return $file->store('documents/' . Auth::id(), 'public');
        }

        return null;
    }


    // View submitted application
    public function showApplication($id)
    {
        $registration = ProvisionalRegistration::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            $enterprise = Enterprise::find($registration->enterprise_type);
            $division   = Divisions::find($registration->region_id);
            $districtsData   = District::find($registration->district_id);
            $CategoryData   = Category::find($registration->application_category);
            // dd($registration);

            $application_form = ApplicationForm::find($registration->application_form_id);

        return view('frontend.Application.provisional.reports', [
            'registration' => $registration,
            'application_form' => $application_form,
            'division' => $division,
            'districtsData' => $districtsData,
            'Category_name' => $CategoryData->name ?? '',
            'enterprise' => $enterprise,
        ]);
    }

   
}
