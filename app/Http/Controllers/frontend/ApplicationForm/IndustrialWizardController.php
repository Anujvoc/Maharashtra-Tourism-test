<?php

namespace App\Http\Controllers\frontend\ApplicationForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\frontend\ApplicationForm\TourismApartment;
use App\Models\frontend\ApplicationForm\AdventureApplication;
use App\Http\Requests\frontend\ApplicationForm\AdventureApplicationRequest;

use App\Models\frontend\ApplicationForm\IndustrialStep1;
use App\Models\frontend\ApplicationForm\IndustrialStep2;
use App\Models\frontend\ApplicationForm\IndustrialStep3;
use App\Models\frontend\ApplicationForm\IndustrialStep4;

use App\Models\Admin\master\Accomodation\SafetyAndSecurity;
use App\Models\Admin\master\Accomodation\AdditionalFeature;
use App\Models\Admin\master\Accomodation\GeneralRequirement;
use App\Models\Admin\master\Accomodation\GuestService;

use App\Models\frontend\ApplicationForm\IndustrialRegistration;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Master\Enterprise;
use App\Models\Admin\Master\Divisions;
use Illuminate\Database\QueryException;
use App\Models\District;
use App\Models\Country;
use App\Models\Admin\ApplicationForm;
use App\Models\frontend\ApplicationForm\Application;
use App\Models\State;
class IndustrialWizardController extends Controller
{
    public function show(Application $application, int $step)
    {
        abort_if($application->user_id !== auth()->id(), 403);

        // prevent jumping too far ahead
        if ($step > $application->current_step + 1) {
            return redirect()->route('industrial.wizard.show', [$application, 'step' => $application->current_step]);
        }

        $application_form = ApplicationForm::find($application->application_form_id);


        $Districts = District::where('state_id', 14)
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();
        $regions = DB::table('divisions')->select('id','name')->get();
        $business_type = Enterprise::orderBy('name', 'asc')->get();


        $SafetyAndSecurity = SafetyAndSecurity::where('is_active',1)->orderBy('name', 'asc')->get();
        $AdditionalFeature = AdditionalFeature::where('is_active',1)->orderBy('name', 'asc')->get();
        $GeneralRequirement = GeneralRequirement::where('is_active',1)->orderBy('name', 'asc')->get();
        $GuestServices = GuestService::where('is_active',1)->orderBy('name', 'asc')->get();



        $States = State::where('id', 14)->first();

        // load step data
        $slug = $this->ensureSlug($application);

        $step1 = IndustrialStep1::where('slug_id', $slug)->where('user_id', auth()->id())->first();
        $step2 = IndustrialStep2::where('slug_id', $slug)->where('user_id', auth()->id())->first();
        $step3 = IndustrialStep3::where('slug_id', $slug)->where('user_id', auth()->id())->first();
        $step4 = IndustrialStep4::where('slug_id', $slug)->where('user_id', auth()->id())->first();

        // dd($step);

        $view = match ($step) {
            1 => 'frontend.Application.Industrial.step1',
            2 => 'frontend.Application.Industrial.step2',
            3 => 'frontend.Application.Industrial.step3',
            4 => 'frontend.Application.Industrial.step4',
            default => abort(404),
        };

        return view($view, compact(
            'application',
            'application_form',
            'States',
            'Districts',
            'business_type',
            'regions',
            'SafetyAndSecurity',
            'AdditionalFeature',
            'GeneralRequirement',
            'GuestServices',
            'step1','step2','step3','step4'
        ));
    }

    /** STEP 1 SAVE */
    public function storeStep1(Request $request, Application $application)
    {

        // dd($request->all());
        $this->authorizeOwner($application);

        $slug = $this->ensureSlug($application);

        $data = $request->validate([
            'email'              => ['required','email'],
            'mobile'             => ['required','digits:10'],
            'hotel_name'         => ['required','string'],
            'company_name'       => ['required','string'],
            'authorized_person'  => ['required','regex:/^[A-Za-z\s]+$/'],
            'region'             => ['required','string'],
            'pincode'            => ['required','digits:6'],
            'total_area'         => ['required','numeric','min:1'],
            'total_employees'    => ['required','integer','min:1'],
            'total_rooms'        => ['required','integer','min:6'],
            'hotel_address'      => ['required','string'],
            'company_address'    => ['required','string'],
            'district'           => ['required','string'],
            'applicant_type'     => ['required','string'],
            'commencement_date'  => ['required','date'],
            'emergency_contact'  => ['required','digits:10'],
            'mseb_consumer_number' => ['required','string'],
            'star_category'      => ['required','string'],
            'electricity_company'=> ['required','string'],
            'property_tax_dept'  => ['required','string'],
            'water_bill_dept'    => ['required','string'],
        ]);

        IndustrialStep1::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'slug_id' => $slug,
            ],
            [
                'application_form_id'   => $application->application_form_id,
                'email'                 => $data['email'],
                'mobile'                => $data['mobile'],
                'hotel_name'            => $data['hotel_name'],
                'hotel_address'         => $data['hotel_address'],
                'company_name'          => $data['company_name'],
                'company_address'       => $data['company_address'],
                'authorized_person'     => $data['authorized_person'],
                'region'                => $data['region'],
                'district'              => $data['district'],
                'applicant_type'        => $data['applicant_type'],
                'pincode'               => $data['pincode'],
                'total_area'            => $data['total_area'],
                'total_employees'       => $data['total_employees'],
                'total_rooms'           => $data['total_rooms'],
                'commencement_date'     => $data['commencement_date'],
                'emergency_contact'     => $data['emergency_contact'],
                'mseb_consumer_number'  => $data['mseb_consumer_number'],
                'star_category'         => $data['star_category'],
                'electricity_company'   => $data['electricity_company'],
                'property_tax_dept'     => $data['property_tax_dept'],
                'water_bill_dept'       => $data['water_bill_dept'],
            ]
        );

        $this->advance($application, 2);

        return redirect()
            ->route('industrial.wizard.show', [$application, 'step' => 2])
            ->with('success', 'Step 1 saved successfully.');
    }

    public function storeStep2(Request $request, Application $application)
    {
        $this->authorizeOwner($application);
        $slug = $this->ensureSlug($application);
        $selectedRequirements = $request->input('requirements', []);
          IndustrialStep2::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'slug_id' => $slug,
            ],
            [
                'application_form_id'   => $application->application_form_id,
                'general_requirements' => json_encode($selectedRequirements) ?? null,

                'bathroom_fixtures'     => $request->boolean('bathroom_fixtures'),
                'full_time_operation'   => $request->boolean('full_time_operation'),
                'elevators'             => $request->boolean('elevators'),
                'emergency_lights'      => $request->boolean('emergency_lights'),
                'cctv'                  => $request->boolean('cctv'),
                'disabled_access'       => $request->boolean('disabled_access'),
            ]
        );


        // mostly checkboxes – validation optional
        IndustrialStep3::updateOrCreate(
            ['user_id' => auth()->id(), 'slug_id' => $slug],
            [
                'application_form_id'   => $application->application_form_id,
                'bath_attached'         => $request->boolean('attached_bathrooms'),
                'bath_hot_cold'         => $request->boolean('hot_cold_water'),
                'water_saving_taps'     => $request->boolean('water_saving_taps'),

                'safety_security' => json_encode($request->input('safety_security', [])),
                'additional_features' => json_encode($request->input('additional_features', [])),
                'guest_services' => json_encode($request->input('guest_services', [])),


                'public_lobby'          => $request->boolean('lounge'),
                'reception'             => $request->boolean('reception'),
                'public_restrooms'      => $request->boolean('public_restrooms'),

                'disabled_room'         => $request->boolean('disabled_room'),
                'fssai_kitchen'         => $request->boolean('fssai_kitchen'),
                'uniforms'              => $request->boolean('uniforms'),

                'pledge_display'        => $request->boolean('pledge_display'),
                'complaint_book'        => $request->boolean('complaint_book'),
                'nodal_officer'         => $request->boolean('nodal_officer_info'),

                'doctor_on_call'        => $request->boolean('doctor_on_call'),
                'police_verification'   => $request->boolean('police_verification'),
                'fire_drills'           => $request->boolean('fire_drills'),
                'first_aid'             => $request->boolean('first_aid'),

                'suite'                 => $request->boolean('suite'),
                'fb_outlet'             => $request->boolean('fb_outlet'),
                'iron_facility'         => $request->boolean('iron_facility'),
                'paid_transport'        => $request->boolean('paid_transport'),
                'business_center'       => $request->boolean('business_center'),
                'conference_facilities' => $request->boolean('conference_facilities'),
                'sewage_treatment'      => $request->boolean('sewage_treatment'),
                'rainwater_harvesting'  => $request->boolean('rainwater_harvesting'),
            ]
        );

        $this->advance($application, 3);

        return redirect()
            ->route('industrial.wizard.show', [$application, 'step' => 3])
            ->with('success', 'Step 2 saved successfully.');
    }

    /** STEP 3 SAVE (documents upload) */
    public function storeStep3(Request $request, Application $application)
    {
        $this->authorizeOwner($application);
        $slug = $this->ensureSlug($application);

        // Row load / create
        $row = IndustrialStep4::firstOrNew([
            'user_id' => auth()->id(),
            'slug_id' => $slug,
        ]);
        $row->application_form_id = $application->application_form_id;

        // Map: input name => [column, directory, required?]
        $files = [
            'pan_card' => [
                'column'   => 'pan_card_path',
                'dir'      => 'industrial/pan',
                'required' => true,
            ],
            'aadhar_card' => [
                'column'   => 'aadhaar_card_path',
                'dir'      => 'industrial/aadhaar',
                'required' => true,
            ],
            'business_registration' => [
                'column'   => 'business_reg_path',
                'dir'      => 'industrial/business',
                'required' => true,
            ],
            'gst_registration' => [
                'column'   => 'gst_cert_path',
                'dir'      => 'industrial/gst',
                'required' => true,
            ],
            'fssai_registration' => [
                'column'   => 'fssai_cert_path',
                'dir'      => 'industrial/fssai',
                'required' => true,
            ],
            'building_certificate' => [
                'column'   => 'building_cert_path',
                'dir'      => 'industrial/building',
                'required' => true,
            ],
            'declaration_form' => [
                'column'   => 'declaration_path',
                'dir'      => 'industrial/declaration',
                'required' => true,
            ],
            'light_bill' => [
                'column'   => 'light_bill_path',
                'dir'      => 'industrial/light',
                'required' => true,
            ],

            // Optional
            'mpcb_certificate' => [
                'column'   => 'mpcb_cert_path',
                'dir'      => 'industrial/mpcb',
                'required' => false,
            ],
            'star_certificate' => [
                'column'   => 'star_cert_path',
                'dir'      => 'industrial/star',
                'required' => false,
            ],
            'water_bill' => [
                'column'   => 'water_bill_path',
                'dir'      => 'industrial/water',
                'required' => false,
            ],
            'fire_noc' => [
                'column'   => 'fire_noc_path',
                'dir'      => 'industrial/fire',
                'required' => false,
            ],
            'property_tax' => [
                'column'   => 'property_tax_path',
                'dir'      => 'industrial/property',
                'required' => false,
            ],
            'electricity_bill' => [
                'column'   => 'electricity_bill_path',
                'dir'      => 'industrial/electricity',
                'required' => false,
            ],
        ];

        // Dynamic validation rules (agar DB me nahi hai to required, warna nullable)
        $rules = [];
        foreach ($files as $input => $meta) {
            $column    = $meta['column'];
            $hasExisting = !empty($row->{$column});
            $isRequiredNow = $meta['required'] && !$hasExisting;

            $rules[$input] = [
                $isRequiredNow ? 'required' : 'nullable',
                'file',
                'max:5120', // 5 MB
                'mimes:jpg,jpeg,png,pdf,doc,docx',
            ];
        }

        Validator::make($request->all(), $rules)->validate();

        // Save each file (storeAs + delete old if new uploaded)
        foreach ($files as $input => $meta) {
            $column = $meta['column'];
            $dir    = $meta['dir'];

            if ($request->hasFile($input)) {

                // delete old if exists
                if (!empty($row->{$column})) {
                    Storage::disk('public')->delete($row->{$column});
                }

                $file = $request->file($input);
                $ext  = $file->getClientOriginalExtension();
                $filename = $slug . '_' . $input . '_' . time() . '.' . $ext;

                $path = $file->storeAs($dir, $filename, 'public');
                $row->{$column} = $path;
            }
        }

        $row->save();

        $this->advance($application, 4);

        return redirect()
            ->route('industrial.wizard.show', [$application, 'step' => 4])
            ->with('success', 'Documents uploaded successfully.');
    }


    /** STEP 4 – FINAL SUBMIT */
    // public function finalSubmit(Request $request, Application $application)
    // {
    //     $this->authorizeOwner($application);
    //     $slug = $this->ensureSlug($application);

    //     $request->validate([
    //         'declaration_accept' => ['accepted'],
    //     ]);

    //     $s1 = IndustrialStep1::where('slug_id', $slug)->where('user_id', auth()->id())->firstOrFail();
    //     $s2 = IndustrialStep2::where('slug_id', $slug)->where('user_id', auth()->id())->first(); // not strictly needed
    //     $s3 = IndustrialStep3::where('slug_id', $slug)->where('user_id', auth()->id())->first();
    //     $s4 = IndustrialStep4::where('slug_id', $slug)->where('user_id', auth()->id())->firstOrFail();

    //     $final = IndustrialRegistration::create([
    //         'user_id'              => auth()->id(),
    //         'application_form_id'  => $application->application_form_id,
    //         'registration_id'      => 'INS-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
    //         'slug_id'              => $slug,
    //         'status'               => 'submitted',
    //         'is_apply'             => true,
    //         'submitted_at'         => now(),

    //         // basic
    //         'email'                => $s1->email,
    //         'mobile'               => $s1->mobile,
    //         'hotel_name'           => $s1->hotel_name,
    //         'hotel_address'        => $s1->hotel_address,
    //         'company_name'         => $s1->company_name,
    //         'company_address'      => $s1->company_address,
    //         'authorized_person'    => $s1->authorized_person,
    //         'region'               => $s1->region,
    //         'district'             => $s1->district,
    //         'applicant_type'       => $s1->applicant_type,
    //         'pincode'              => $s1->pincode,
    //         'total_area'           => $s1->total_area,
    //         'total_employees'      => $s1->total_employees,
    //         'total_rooms'          => $s1->total_rooms,
    //         'commencement_date'    => $s1->commencement_date,
    //         'emergency_contact'    => $s1->emergency_contact,
    //         'mseb_consumer_number' => $s1->mseb_consumer_number,
    //         'star_category'        => $s1->star_category,
    //         'electricity_company'  => $s1->electricity_company,
    //         'property_tax_dept'    => $s1->property_tax_dept,
    //         'water_bill_dept'      => $s1->water_bill_dept,

    //         // documents
    //         'pan_card_path'        => $s4->pan_card_path,
    //         'aadhaar_card_path'    => $s4->aadhaar_card_path,
    //         'gst_cert_path'        => $s4->gst_cert_path,
    //         'fssai_cert_path'      => $s4->fssai_cert_path,
    //         'business_reg_path'    => $s4->business_reg_path,
    //         'declaration_path'     => $s4->declaration_path,
    //         'mpcb_cert_path'       => $s4->mpcb_cert_path,
    //         'light_bill_path'      => $s4->light_bill_path,
    //         'fire_noc_path'        => $s4->fire_noc_path,
    //         'property_tax_path'    => $s4->property_tax_path,
    //         'star_cert_path'       => $s4->star_cert_path,
    //         'water_bill_path'      => $s4->water_bill_path,
    //         'electricity_bill_path'=> $s4->electricity_bill_path,
    //         'building_cert_path'   => $s4->building_cert_path,
    //     ]);

    //     // final submit ke baad sab temp rows delete
    //     IndustrialStep1::where('slug_id', $slug)->where('user_id', auth()->id())->delete();
    //     IndustrialStep2::where('slug_id', $slug)->where('user_id', auth()->id())->delete();
    //     IndustrialStep3::where('slug_id', $slug)->where('user_id', auth()->id())->delete();
    //     IndustrialStep4::where('slug_id', $slug)->where('user_id', auth()->id())->delete();
    //     Application::where('slug_id', $slug)->where('user_id', auth()->id())->delete();

    //     // application current_step / progress update
    //     $this->advance($application, 4);

    //     return redirect()
    //         ->route('application.index')
    //         ->with('success', 'Application submitted successfully.');
    // }

    /** STEP 4 – FINAL SUBMIT */
public function finalSubmit(Request $request, Application $application)
{
    $this->authorizeOwner($application);
    $slug   = $this->ensureSlug($application);
    $userId = auth()->id();

    $request->validate([
        'declaration_accept' => ['accepted'],
    ]);

    try {
        DB::beginTransaction();

        // Lock rows so that koi race condition na ho
        $s1 = IndustrialStep1::where('slug_id', $slug)
            ->where('user_id', $userId)
            ->lockForUpdate()
            ->firstOrFail();

        $s2 = IndustrialStep2::where('slug_id', $slug)
            ->where('user_id', $userId)
            ->lockForUpdate()
            ->first(); // optional

        $s3 = IndustrialStep3::where('slug_id', $slug)
            ->where('user_id', $userId)
            ->lockForUpdate()
            ->first(); // optional

        $s4 = IndustrialStep4::where('slug_id', $slug)
            ->where('user_id', $userId)
            ->lockForUpdate()
            ->firstOrFail();

        $final = IndustrialRegistration::create([
            'user_id'              => $userId,
            'application_form_id'  => $application->application_form_id,
            'registration_id'      => 'INS-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
            'slug_id'              => $slug,
            'status'               => 'submitted',
            'is_apply'             => true,
            'submitted_at'         => now(),


            'email'                => $s1->email,
            'mobile'               => $s1->mobile,
            'hotel_name'           => $s1->hotel_name,
            'hotel_address'        => $s1->hotel_address,
            'company_name'         => $s1->company_name,
            'company_address'      => $s1->company_address,
            'authorized_person'    => $s1->authorized_person,
            'region'               => $s1->region,
            'district'             => $s1->district,
            'applicant_type'       => $s1->applicant_type,
            'pincode'              => $s1->pincode,
            'total_area'           => $s1->total_area,
            'total_employees'      => $s1->total_employees,
            'total_rooms'          => $s1->total_rooms,
            'commencement_date'    => $s1->commencement_date,
            'emergency_contact'    => $s1->emergency_contact,
            'mseb_consumer_number' => $s1->mseb_consumer_number,
            'star_category'        => $s1->star_category,
            'electricity_company'  => $s1->electricity_company,
            'property_tax_dept'    => $s1->property_tax_dept,
            'water_bill_dept'      => $s1->water_bill_dept,

            'general_requirements' => optional($s2)->general_requirements,
            'bathroom_fixtures'    => optional($s2)->bathroom_fixtures,
            'full_time_operation'  => optional($s2)->full_time_operation,
            'elevators'            => optional($s2)->elevators,
            'emergency_lights'     => optional($s2)->emergency_lights,
            'cctv'                 => optional($s2)->cctv,
            'disabled_access'      => optional($s2)->disabled_access,

            'bath_attached'         => optional($s3)->bath_attached,
            'bath_hot_cold'         => optional($s3)->bath_hot_cold,
            'water_saving_taps'     => optional($s3)->water_saving_taps,

            'safety_security'       => optional($s3)->safety_security,
            'additional_features'   => optional($s3)->additional_features,
            'guest_services'        => optional($s3)->guest_services,

            'public_lobby'          => optional($s3)->public_lobby,
            'reception'             => optional($s3)->reception,
            'public_restrooms'      => optional($s3)->public_restrooms,

            'disabled_room'         => optional($s3)->disabled_room,
            'fssai_kitchen'         => optional($s3)->fssai_kitchen,
            'uniforms'              => optional($s3)->uniforms,

            'pledge_display'        => optional($s3)->pledge_display,
            'complaint_book'        => optional($s3)->complaint_book,
            'nodal_officer'         => optional($s3)->nodal_officer,

            'doctor_on_call'        => optional($s3)->doctor_on_call,
            'police_verification'   => optional($s3)->police_verification,
            'fire_drills'           => optional($s3)->fire_drills,
            'first_aid'             => optional($s3)->first_aid,

            'suite'                 => optional($s3)->suite,
            'fb_outlet'             => optional($s3)->fb_outlet,
            'iron_facility'         => optional($s3)->iron_facility,
            'paid_transport'        => optional($s3)->paid_transport,
            'business_center'       => optional($s3)->business_center,
            'conference_facilities' => optional($s3)->conference_facilities,
            'sewage_treatment'      => optional($s3)->sewage_treatment,
            'rainwater_harvesting'  => optional($s3)->rainwater_harvesting,

            'pan_card_path'         => $s4->pan_card_path,
            'aadhaar_card_path'     => $s4->aadhaar_card_path,
            'gst_cert_path'         => $s4->gst_cert_path,
            'fssai_cert_path'       => $s4->fssai_cert_path,
            'business_reg_path'     => $s4->business_reg_path,
            'declaration_path'      => $s4->declaration_path,
            'mpcb_cert_path'        => $s4->mpcb_cert_path,
            'light_bill_path'       => $s4->light_bill_path,
            'fire_noc_path'         => $s4->fire_noc_path,
            'property_tax_path'     => $s4->property_tax_path,
            'star_cert_path'        => $s4->star_cert_path,
            'water_bill_path'       => $s4->water_bill_path,
            'electricity_bill_path' => $s4->electricity_bill_path,
            'building_cert_path'    => $s4->building_cert_path,
        ]);

        IndustrialStep1::where('slug_id', $slug)->where('user_id', $userId)->delete();
        IndustrialStep2::where('slug_id', $slug)->where('user_id', $userId)->delete();
        IndustrialStep3::where('slug_id', $slug)->where('user_id', $userId)->delete();
        IndustrialStep4::where('slug_id', $slug)->where('user_id', $userId)->delete();
        Application::where('slug_id', $slug)->where('user_id', $userId)->delete();

        $this->advance($application, 4);

        DB::commit();
        return redirect()->route('applications.index')->with('success', 'Application saved');
        return redirect()
            ->route('applications.index')
            ->with('success', 'Application submitted successfully.');

    } catch (\Throwable $e) {

        DB::rollBack();

        Log::error('Industrial final submit failed', [
            'slug_id'  => $slug,
            'user_id'  => $userId,
            'message'  => $e->getMessage(),
        ]);

        return back()
            ->withInput()
            ->with('error', 'Something went wrong while submitting your application. Please try again.');
    }
}

    protected function advance(Application $app, int $nextStep): void
    {
        if ($nextStep > $app->current_step) {
            $app->forceFill(['current_step' => $nextStep])->save();
        }
    }

    protected function ensureSlug(Application $application): string
    {
        if (!$application->slug_id) {
            $application->slug_id = 'INS-' . Str::random(8);
            $application->save();
        }
        return $application->slug_id;
    }

    protected function authorizeOwner(Application $application): void
    {
        abort_if($application->user_id !== auth()->id(), 403);
    }

    public function report($id)
    {
        $registration = IndustrialRegistration::find($id);
        if (auth()->id() !== $registration->user_id && !auth()->user()->is_admin) {
            abort(403, 'You are not allowed to view this report.');
        }

        $application_form = ApplicationForm::find($registration->application_form_id);
        $GeneralRequirement = GeneralRequirement::get();
        $GuestServices      = GuestService::get();
        $SafetyAndSecurity  = SafetyAndSecurity::get();
        $AdditionalFeature  = AdditionalFeature::get();

        return view('frontend.Application.Industrial.report', [
            'registration'       => $registration,
            'application_form'   => $application_form,
            'GeneralRequirement' => $GeneralRequirement,
            'GuestServices'      => $GuestServices,
            'SafetyAndSecurity'  => $SafetyAndSecurity,
            'AdditionalFeature'  => $AdditionalFeature,
        ]);
    }

}
