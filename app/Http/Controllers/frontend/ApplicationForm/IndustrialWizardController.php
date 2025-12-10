<?php

namespace App\Http\Controllers\frontend\ApplicationForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\frontend\ApplicationForm\TourismApartment;
use App\Models\frontend\ApplicationForm\AdventureApplication;
use App\Http\Requests\frontend\ApplicationForm\AdventureApplicationRequest;

use App\Models\frontend\ApplicationForm\IndustrialStep1;
use App\Models\frontend\ApplicationForm\IndustrialStep2;
use App\Models\frontend\ApplicationForm\IndustrialStep3;
use App\Models\frontend\ApplicationForm\IndustrialStep4;


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

        $States = State::where('id', 14)->first();

        // load step data
        $slug = $this->ensureSlug($application);

        $step1 = IndustrialStep1::where('slug_id', $slug)->where('user_id', auth()->id())->first();
        $step2 = IndustrialStep2::where('slug_id', $slug)->where('user_id', auth()->id())->first();
        $step3 = IndustrialStep3::where('slug_id', $slug)->where('user_id', auth()->id())->first();
        $step4 = IndustrialStep4::where('slug_id', $slug)->where('user_id', auth()->id())->first();

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
            'step1','step2','step3','step4'
        ));
    }

    /** STEP 1 SAVE */
    public function storeStep1(Request $request, Application $application)
    {

        dd($request->all());
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

            // general requirement checkboxes (optional)
            // 'min_rooms'             => ['nullable'],
            // 'room_size'             => ['nullable'],
            // 'bathroom_size'         => ['nullable'],
            // 'bathroom_fixtures'     => ['nullable'],
            // 'full_time_operation'   => ['nullable'],
            // 'elevators'             => ['nullable'],
            // 'electricity_availability'=>['nullable'],
            // 'emergency_lights'      => ['nullable'],
            // 'cctv'                  => ['nullable'],
            // 'disabled_access'       => ['nullable'],
            // 'security_guards'       => ['nullable'],
        ]);

        // BASIC fields in step1 table
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

        // general requirements in step2 table
        IndustrialStep2::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'slug_id' => $slug,
            ],
            [
                'application_form_id'   => $application->application_form_id,
                'min_rooms'             => $request->boolean('min_rooms'),
                'room_size_ok'          => $request->boolean('room_size'),
                'bathroom_size_ok'      => $request->boolean('bathroom_size'),
                'bathroom_fixtures'     => $request->boolean('bathroom_fixtures'),
                'full_time_operation'   => $request->boolean('full_time_operation'),
                'elevators'             => $request->boolean('elevators'),
                'electricity_availability'=> $request->boolean('electricity_availability'),
                'emergency_lights'      => $request->boolean('emergency_lights'),
                'cctv'                  => $request->boolean('cctv'),
                'disabled_access'       => $request->boolean('disabled_access'),
                'security_guards'       => $request->boolean('security_guards'),
            ]
        );

        $this->advance($application, 2);

        return redirect()
            ->route('industrial.wizard.show', [$application, 'step' => 2])
            ->with('success', 'Step 1 saved successfully.');
    }

    /** STEP 2 SAVE (facilities/services) */
    public function storeStep2(Request $request, Application $application)
    {
        $this->authorizeOwner($application);
        $slug = $this->ensureSlug($application);

        // mostly checkboxes – validation optional
        IndustrialStep3::updateOrCreate(
            ['user_id' => auth()->id(), 'slug_id' => $slug],
            [
                'application_form_id'   => $application->application_form_id,
                'bath_attached'         => $request->boolean('attached_bathrooms'),
                'bath_hot_cold'         => $request->boolean('hot_cold_water'),
                'water_saving_taps'     => $request->boolean('water_saving_taps'),

                'public_lobby'          => $request->boolean('lounge'),
                'reception'             => $request->boolean('reception'),
                'public_restrooms'      => $request->boolean('public_restrooms'),

                'disabled_room'         => $request->boolean('disabled_room'),
                'fssai_kitchen'         => $request->boolean('fssai_kitchen'),
                'uniforms'              => $request->boolean('uniforms'),

                'pledge_display'        => $request->boolean('pledge_display'),
                'complaint_book'        => $request->boolean('complaint_book'),
                'nodal_officer'         => $request->boolean('nodal_officer'),

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

        $request->validate([
            'pan_card'            => ['nullable','file','max:5120'],
            'business_registration'=>['nullable','file','max:5120'],
            'fssai_registration'  => ['nullable','file','max:5120'],
            'declaration_form'    => ['nullable','file','max:5120'],
            'mpcb_certificate'    => ['nullable','file','max:5120'],
            'star_certificate'    => ['nullable','file','max:5120'],
            'water_bill'          => ['nullable','file','max:5120'],
            'aadhar_card'         => ['nullable','file','max:5120'],
            'gst_registration'    => ['nullable','file','max:5120'],
            'building_certificate'=> ['nullable','file','max:5120'],
            'light_bill'          => ['nullable','file','max:5120'],
            'fire_noc'            => ['nullable','file','max:5120'],
            'property_tax'        => ['nullable','file','max:5120'],
            'electricity_bill'    => ['nullable','file','max:5120'],
        ]);

        $row = IndustrialStep4::firstOrNew([
            'user_id' => auth()->id(),
            'slug_id' => $slug,
        ]);
        $row->application_form_id = $application->application_form_id;

        // upload helpers
        if ($request->hasFile('pan_card')) {
            $row->pan_card_path = $request->file('pan_card')->store('industrial/pan', 'public');
        }
        if ($request->hasFile('aadhar_card')) {
            $row->aadhaar_card_path = $request->file('aadhar_card')->store('industrial/aadhaar', 'public');
        }
        if ($request->hasFile('gst_registration')) {
            $row->gst_cert_path = $request->file('gst_registration')->store('industrial/gst', 'public');
        }
        if ($request->hasFile('fssai_registration')) {
            $row->fssai_cert_path = $request->file('fssai_registration')->store('industrial/fssai', 'public');
        }
        if ($request->hasFile('business_registration')) {
            $row->business_reg_path = $request->file('business_registration')->store('industrial/business', 'public');
        }
        if ($request->hasFile('declaration_form')) {
            $row->declaration_path = $request->file('declaration_form')->store('industrial/declaration', 'public');
        }
        if ($request->hasFile('mpcb_certificate')) {
            $row->mpcb_cert_path = $request->file('mpcb_certificate')->store('industrial/mpcb', 'public');
        }
        if ($request->hasFile('light_bill')) {
            $row->light_bill_path = $request->file('light_bill')->store('industrial/light', 'public');
        }
        if ($request->hasFile('fire_noc')) {
            $row->fire_noc_path = $request->file('fire_noc')->store('industrial/fire', 'public');
        }
        if ($request->hasFile('property_tax')) {
            $row->property_tax_path = $request->file('property_tax')->store('industrial/property', 'public');
        }
        if ($request->hasFile('star_certificate')) {
            $row->star_cert_path = $request->file('star_certificate')->store('industrial/star', 'public');
        }
        if ($request->hasFile('water_bill')) {
            $row->water_bill_path = $request->file('water_bill')->store('industrial/water', 'public');
        }
        if ($request->hasFile('electricity_bill')) {
            $row->electricity_bill_path = $request->file('electricity_bill')->store('industrial/electricity', 'public');
        }
        if ($request->hasFile('building_certificate')) {
            $row->building_cert_path = $request->file('building_certificate')->store('industrial/building', 'public');
        }

        $row->save();

        $this->advance($application, 4);

        return redirect()
            ->route('industrial.wizard.show', [$application, 'step' => 4])
            ->with('success', 'Documents uploaded successfully.');
    }

    /** STEP 4 – FINAL SUBMIT */
    public function finalSubmit(Request $request, Application $application)
    {
        $this->authorizeOwner($application);
        $slug = $this->ensureSlug($application);

        $request->validate([
            'declaration_accept' => ['accepted'],
        ]);

        $s1 = IndustrialStep1::where('slug_id', $slug)->where('user_id', auth()->id())->firstOrFail();
        $s2 = IndustrialStep2::where('slug_id', $slug)->where('user_id', auth()->id())->first(); // not strictly needed
        $s3 = IndustrialStep3::where('slug_id', $slug)->where('user_id', auth()->id())->first();
        $s4 = IndustrialStep4::where('slug_id', $slug)->where('user_id', auth()->id())->firstOrFail();

        $final = IndustrialRegistration::create([
            'user_id'              => auth()->id(),
            'application_form_id'  => $application->application_form_id,
            'registration_id'      => 'ind-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
            'slug_id'              => $slug,
            'status'               => 'submitted',
            'is_apply'             => true,
            'submitted_at'         => now(),

            // basic
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

            // documents
            'pan_card_path'        => $s4->pan_card_path,
            'aadhaar_card_path'    => $s4->aadhaar_card_path,
            'gst_cert_path'        => $s4->gst_cert_path,
            'fssai_cert_path'      => $s4->fssai_cert_path,
            'business_reg_path'    => $s4->business_reg_path,
            'declaration_path'     => $s4->declaration_path,
            'mpcb_cert_path'       => $s4->mpcb_cert_path,
            'light_bill_path'      => $s4->light_bill_path,
            'fire_noc_path'        => $s4->fire_noc_path,
            'property_tax_path'    => $s4->property_tax_path,
            'star_cert_path'       => $s4->star_cert_path,
            'water_bill_path'      => $s4->water_bill_path,
            'electricity_bill_path'=> $s4->electricity_bill_path,
            'building_cert_path'   => $s4->building_cert_path,
        ]);

        // final submit ke baad sab temp rows delete
        IndustrialStep1::where('slug_id', $slug)->where('user_id', auth()->id())->delete();
        IndustrialStep2::where('slug_id', $slug)->where('user_id', auth()->id())->delete();
        IndustrialStep3::where('slug_id', $slug)->where('user_id', auth()->id())->delete();
        IndustrialStep4::where('slug_id', $slug)->where('user_id', auth()->id())->delete();

        // application current_step / progress update
        $this->advance($application, 4);

        return redirect()
            ->route('industrial.wizard.show', [$application, 'step' => 4])
            ->with('success', 'Application submitted successfully.');
    }

    /** -------- helpers ---------- */

    protected function advance(Application $app, int $nextStep): void
    {
        if ($nextStep > $app->current_step) {
            $app->forceFill(['current_step' => $nextStep])->save();
        }
    }

    protected function ensureSlug(Application $application): string
    {
        if (!$application->slug_id) {
            $application->slug_id = 'ind-' . Str::random(8);
            $application->save();
        }
        return $application->slug_id;
    }

    protected function authorizeOwner(Application $application): void
    {
        abort_if($application->user_id !== auth()->id(), 403);
    }
}
