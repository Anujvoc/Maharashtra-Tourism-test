<?php

namespace App\Http\Controllers\frontend\ApplicationForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\frontend\ApplicationForm\ProvisionalRegistration;
use App\Models\frontend\ApplicationForm\StampDutyApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Admin\Master\Category;
use App\Models\Admin\Master\Enterprise;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin\ApplicationForm;
use Carbon\Carbon;


use App\Models\Admin\Master\Divisions;
use App\Models\District;

use function Pest\Laravel\json;

class StampDutyWizardController extends Controller
{
    protected int $totalSteps = 6;

public function create(Request $request, $id, int $step = 1, $applicationId = null)
{
    $step = max(1, min($this->totalSteps, $step));

    $application = null;
    if ($applicationId) {
        $application = StampDutyApplication::where('user_id', Auth::id())
            ->findOrFail($applicationId);
    }

    $application_form = ApplicationForm::where('is_active', 1)
        ->where('id', $id)
        ->firstOrFail();

    // Progress
    $progress = $application?->progress ?? ['done' => 0, 'total' => $this->totalSteps];
    $done     = $progress['done'] ?? 0;

    // master lists
    $districts = DB::table('districts')->where('state_id',14)->select('id', 'name')->get();
    $states    = DB::table('states')->where('id',14)->select('id', 'name')->get();
    $regions   = DB::table('divisions')->select('id', 'name')->get() ?? collect();

    $user        = Auth::user();
    $enterprises = Enterprise::select('id','name')->get();
    $categories  = Category::all();

    $view = "frontend.Application.stamp_duty.wizard_step{$step}";

    return view($view, [
        'step'            => $step,
        'application'     => $application,
        'progress'        => $progress,
        'done'            => $done,
        'total'           => $this->totalSteps,
        'districts'       => $districts,
        'states'          => $states,
        'regions'         => $regions,
        'application_form'=> $application_form,
        'enterprises'     => $enterprises,
        'categories'      => $categories,
    ]);
}

    public function store(Request $request, int $step)
    {
        $step = max(1, min($this->totalSteps, $step));

        $applicationId = $request->input('application_id');

        $application = $applicationId
            ? StampDutyApplication::where('user_id', Auth::id())->findOrFail($applicationId)
            : new StampDutyApplication(['user_id' => Auth::id()]);

        // Validate according to step
        $validated = $request->validate($this->rulesForStep($step), $this->messages());

        // Fill data per step
        $this->fillStepData($application, $validated, $step, $request);

        $applicationId      = $request->input('application_id');
        $applicationFormId  = $request->input('application_form_id');
        if ($step === 1 && empty($application->registration_id)) {
            $application->application_form_id = $applicationFormId;
            $application->registration_id = 'STD-' . str_pad(
                random_int(0, 99999999),
                8,
                '0',
                STR_PAD_LEFT
            );
            $application->slug_id = 'STD-' . Str::upper(Str::random(8));
        }


        // Progress
        $progress = $application->progress ?? ['done' => 0, 'total' => $this->totalSteps];
        $progress['done']  = max($progress['done'] ?? 0, $step);
        $progress['total'] = $this->totalSteps;

        $application->current_step = $step;
        $application->progress     = $progress;
        $application->status       = 'draft';

        $application->save();

        $nextStep = $step + 1;

        if ($nextStep > $this->totalSteps) {
            // redirect to review page
            return redirect()
                ->route('stamp-duty.review', $application->id)
                ->with('success', 'All steps saved. Please review your application.');
        }


        return redirect()
        ->route('stamp-duty.wizard', [
            'id'          => $applicationFormId,
            'step'        => $nextStep,
            'application' => $application->id,
        ])
        ->with('success', 'Step saved successfully. Continue to next step.');
    }

    public function review($applicationId)
    {
        $application = StampDutyApplication::where('user_id', Auth::id())
            ->findOrFail($applicationId);

        $districts = DB::table('districts')->select('id', 'name')->get()->keyBy('id');
        $states    = DB::table('states')->select('id', 'name')->get()->keyBy('id');
        $regions   = DB::table('divisions')->select('id', 'name')->get()->keyBy('id');

        $progress  = $application->progress ?? ['done' => 0, 'total' => $this->totalSteps];
        $applicant_types = Enterprise::find($application->applicant_type);
        return view('frontend.Application.stamp_duty.wizard_review', [
            'application' => $application,
            'districts'   => $districts,
            'states'      => $states,
            'regions'     => $regions,
            'progress'    => $progress,
            'step'        => $this->totalSteps,
            'done'        => $progress['done'] ?? 0,
            'total'       => $this->totalSteps,
            'applicant_type'       => $applicant_types->name ?? '',
        ]);
    }

    public function submit(Request $request, $applicationId)
    {
        $application = StampDutyApplication::where('user_id', Auth::id())
            ->findOrFail($applicationId);

        $request->validate([
            'declaration_accept' => ['required', 'accepted'],
        ]);

        $application->declaration_accepted = true;
        $application->status               = 'submitted';
        $application->is_completed         = true;
        $progress                          = $application->progress ?? ['done' => 0, 'total' => $this->totalSteps];
        $progress['done']                  = $this->totalSteps;
        $application->progress             = $progress;
        $application->submitted_at             = now();
        $application->is_apply             = true;
        $application->save();

        return redirect()
        ->route('applications.index')
            ->with('success', 'Application submitted successfully.');
    }

    // ------------------------ Helpers ------------------------

    protected function rulesForStep(int $step): array
    {
        switch ($step) {
            case 1:
                return [
                    'region_id'        => ['required'],
                    'district_id'      => ['required'],
                    'company_name'     => ['required','regex:/^[A-Za-z0-9\s\.,&\'-]+$/'],
                    'registration_no'  => ['required','string','max:191'],
                    'application_date' => ['required','date'],
                    'applicant_type'   => ['required','string'],
                    'agreement_type'   => ['required','string'],
                ];

            case 2:
                return [
                    'c_address'  => ['required','string'],
                    'c_city'     => ['required','regex:/^[A-Za-z\s]+$/'],
                    'c_taluka'   => ['required','regex:/^[A-Za-z\s]+$/'],
                    'c_district' => ['required','regex:/^[A-Za-z\s]+$/'],
                    'c_state'    => ['required','regex:/^[A-Za-z\s]+$/'],
                    'c_pincode'  => ['required','regex:/^[1-9][0-9]{5}$/'],
                    'c_mobile'   => ['required','regex:/^[6-9][0-9]{9}$/'],
                    'c_email'    => ['required','email'],
                    'p_address'  => ['required','string'],
                    'p_city'     => ['required','regex:/^[A-Za-z\s]+$/'],
                    'p_taluka'   => ['required','regex:/^[A-Za-z\s]+$/'],
                    'p_district' => ['required','regex:/^[A-Za-z\s]+$/'],
                    'p_state'    => ['required','regex:/^[A-Za-z\s]+$/'],
                    'p_pincode'  => ['required','regex:/^[1-9][0-9]{5}$/'],
                    'p_mobile'   => ['required','regex:/^[6-9][0-9]{9}$/'],
                    'p_email'    => ['required','email'],
                ];

            case 3:
                return [
                    'land_gat'      => ['required','string'],
                    'land_village'  => ['required','regex:/^[A-Za-z\s]+$/'],
                    'land_taluka'   => ['required','regex:/^[A-Za-z\s]+$/'],
                    'land_district' => ['required','regex:/^[A-Za-z\s]+$/'],
                    'area_a'        => ['required','numeric','min:0'],
                    'area_b'        => ['required','numeric','min:0'],
                    'area_c'        => ['required','numeric','min:0'],
                    'area_d'        => ['required','numeric','min:0'],
                    'area_e'        => ['required','numeric','min:0'],
                    'na_area'       => ['nullable','numeric','min:0'],
                ];

            case 4:
                return [
                    'estimated_project_cost' => ['required','numeric','min:0'],
                    'proposed_employment'    => ['required','integer','min:0'],
                    'tourism_activities'     => ['required','string'],
                    'incentives_availed'     => ['nullable','string'],
                    'existed_before'         => ['required','boolean'],
                    'eligibility_cert_no'    => ['required_if:existed_before,1','nullable','string'],
                    'eligibility_date'       => ['required_if:existed_before,1','nullable','date'],
                    'present_status'         => ['required_if:existed_before,1','nullable','string'],
                    'cost_land'              => ['required','numeric','min:0'],
                    'cost_building'          => ['required','numeric','min:0'],
                    'cost_machinery'         => ['required','numeric','min:0'],
                    'cost_electrical'        => ['required','numeric','min:0'],
                    'cost_misc'              => ['required','numeric','min:0'],
                    'cost_other'             => ['required','numeric','min:0'],
                    'project_employment'     => ['required','integer','min:0'],
                    'noc_purpose'            => ['required','string'],
                    'noc_authority'          => ['required','string'],
                ];



            case 5: // Documents
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

                $rules = [];

                $application = null;
                $applicationId = request()->input('application_id');

                if ($applicationId) {
                    $application = StampDutyApplication::where('user_id', Auth::id())
                        ->find($applicationId);
                }

                foreach ($fileFields as $field) {
                    $hasExisting = $application && !empty($application->{$field});

                    $rules[$field] = [
                        $hasExisting ? 'nullable' : 'required',
                        'file',
                        'max:2048',
                        'mimes:jpg,jpeg,png,pdf',
                    ];
                }

                return $rules;



            case 6:
                $application = null;
                $applicationId = request()->input('application_id');

                if ($applicationId) {
                    $application = StampDutyApplication::where('user_id', Auth::id())
                        ->find($applicationId);
                }

                $hasSignature = $application && !empty($application->signature_path);

                return [
                    'name_designation'       => ['required','regex:/^[A-Za-z\s\.]+$/'],
                    'signature'              => [
                        $hasSignature ? 'nullable' : 'required',
                        'file',
                        'max:2048',
                        'mimes:jpg,jpeg,png,pdf',
                    ],

                    'stamp'                  => [
                        'nullable',
                        'file',
                        'max:2048',
                        'mimes:jpg,jpeg,png,pdf',
                    ],

                    // 'aff_name'               => ['required','regex:/^[A-Za-z\s\.]+$/'],
                    // 'aff_company'            => ['required','string'],
                    // 'aff_registered_office'  => ['required','string'],
                    // 'aff_land_area'          => ['required','numeric','min:0'],
                    // 'aff_cts'                => ['required','string'],
                    // 'aff_village'            => ['required','regex:/^[A-Za-z\s]+$/'],
                    // 'aff_taluka'             => ['required','regex:/^[A-Za-z\s]+$/'],
                    // 'aff_district'           => ['required','regex:/^[A-Za-z\s]+$/'],
                ];



        }

        return [];
    }

    protected function messages(): array
    {
        return [
            'company_name.regex' => 'Enter valid company name (letters, numbers, spaces, .,&,-)',
            'c_city.regex'       => 'Only letters and spaces allowed',
            'c_taluka.regex'     => 'Only letters and spaces allowed',
            'c_district.regex'   => 'Only letters and spaces allowed',
            'c_state.regex'      => 'Only letters and spaces allowed',
            'c_pincode.regex'    => 'Enter 6-digit pin code',
            'c_mobile.regex'     => 'Enter valid 10-digit mobile number starting with 6-9',
            'p_city.regex'       => 'Only letters and spaces allowed',
            'p_taluka.regex'     => 'Only letters and spaces allowed',
            'p_district.regex'   => 'Only letters and spaces allowed',
            'p_state.regex'      => 'Only letters and spaces allowed',
            'p_pincode.regex'    => 'Enter 6-digit pin code',
            'p_mobile.regex'     => 'Enter valid 10-digit mobile number starting with 6-9',
            'land_village.regex' => 'Only letters and spaces allowed',
            'land_taluka.regex'  => 'Only letters and spaces allowed',
            'land_district.regex'=> 'Only letters and spaces allowed',
            'name_designation.regex' => 'Only letters, spaces and dots allowed',
            'aff_name.regex'     => 'Only letters, spaces and dots allowed',
            'aff_village.regex'  => 'Only letters and spaces allowed',
            'aff_taluka.regex'   => 'Only letters and spaces allowed',
            'aff_district.regex' => 'Only letters and spaces allowed',
        ];
    }

    protected function fillStepData(StampDutyApplication $app, array $validated, int $step, Request $request): void
    {
        switch ($step) {
            case 1:
                $app->fill([
                    'region_id'        => $validated['region_id'],
                    'district_id'      => $validated['district_id'],
                    'company_name'     => $validated['company_name'],
                    'registration_no'  => $validated['registration_no'],
                    'application_date' => $validated['application_date'],
                    'applicant_type'   => $validated['applicant_type'],
                    'agreement_type'   => $validated['agreement_type'],
                ]);
                break;

            case 2:
                $app->fill([
                    'c_address'  => $validated['c_address'],
                    'c_city'     => $validated['c_city'],
                    'c_taluka'   => $validated['c_taluka'],
                    'c_district' => $validated['c_district'],
                    'c_state'    => $validated['c_state'],
                    'c_pincode'  => $validated['c_pincode'],
                    'c_mobile'   => $validated['c_mobile'],
                    'c_phone'    => $request->input('c_phone'),
                    'c_email'    => $validated['c_email'],
                    'c_fax'      => $request->input('c_fax'),

                    'p_address'  => $validated['p_address'],
                    'p_city'     => $validated['p_city'],
                    'p_taluka'   => $validated['p_taluka'],
                    'p_district' => $validated['p_district'],
                    'p_state'    => $validated['p_state'],
                    'p_pincode'  => $validated['p_pincode'],
                    'p_mobile'   => $validated['p_mobile'],
                    'p_phone'    => $request->input('p_phone'),
                    'p_email'    => $validated['p_email'],
                    'p_website'  => $request->input('p_website'),
                ]);
                break;

            case 3:
                $app->fill([
                    'land_gat'      => $validated['land_gat'],
                    'land_village'  => $validated['land_village'],
                    'land_taluka'   => $validated['land_taluka'],
                    'land_district' => $validated['land_district'],
                    'area_a'        => $validated['area_a'],
                    'area_b'        => $validated['area_b'],
                    'area_c'        => $validated['area_c'],
                    'area_d'        => $validated['area_d'],
                    'area_e'        => $validated['area_e'],

                    'na_gat'        => $request->input('na_gat'),
                    'na_village'    => $request->input('na_village'),
                    'na_taluka'     => $request->input('na_taluka'),
                    'na_district'   => $request->input('na_district'),
                    'na_area'       => $request->input('na_area'),
                ]);
                break;

            case 4:
                $app->fill([
                    'estimated_project_cost' => $validated['estimated_project_cost'],
                    'proposed_employment'    => $validated['proposed_employment'],
                    'tourism_activities'     => $validated['tourism_activities'],
                    'incentives_availed'     => $request->input('incentives_availed'),
                    'existed_before'         => $validated['existed_before'],
                    'eligibility_cert_no'    => $request->input('eligibility_cert_no'),
                    'eligibility_date'       => $request->input('eligibility_date'),
                    'present_status'         => $request->input('present_status'),

                    'cost_land'              => $validated['cost_land'],
                    'cost_building'          => $validated['cost_building'],
                    'cost_machinery'         => $validated['cost_machinery'],
                    'cost_electrical'        => $validated['cost_electrical'],
                    'cost_misc'              => $validated['cost_misc'],
                    'cost_other'             => $validated['cost_other'],
                    'project_employment'     => $validated['project_employment'],
                    'noc_purpose'            => $validated['noc_purpose'],
                    'noc_authority'          => $validated['noc_authority'],
                ]);
                break;

        case 5:
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
        break;

            case 6:
                $app->fill([
                    'name_designation'      => $validated['name_designation'],
                    // 'aff_name'              => $validated['aff_name'],
                    // 'aff_company'           => $validated['aff_company'],
                    // 'aff_registered_office' => $validated['aff_registered_office'],
                    // 'aff_land_area'         => $validated['aff_land_area'],
                    // 'aff_cts'               => $validated['aff_cts'],
                    // 'aff_village'           => $validated['aff_village'],
                    // 'aff_taluka'            => $validated['aff_taluka'],
                    // 'aff_district'          => $validated['aff_district'],
                ]);

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
                break;
        }
    }

    public function reports($id)
    {
        $registration = StampDutyApplication::findOrFail($id);

        $application_form = ApplicationForm::find($registration->application_form_id);
        $region = Divisions::find($registration->region_id);
        $district = District::find($registration->district_id);
        $applicant_types = Enterprise::find($registration->applicant_type);

        return view('frontend.Application.stamp_duty.reports', compact('registration',
        'application_form',
        'region',
        'applicant_types',
        'district'
    ));
    }

}

