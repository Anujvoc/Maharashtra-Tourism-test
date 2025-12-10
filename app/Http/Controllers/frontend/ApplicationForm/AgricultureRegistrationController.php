<?php

namespace App\Http\Controllers\frontend\ApplicationForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Models\frontend\ApplicationForm\TourismApartment;
use App\Models\frontend\ApplicationForm\AdventureApplication;
use App\Models\frontend\ApplicationForm\AgricultureRegistration;
use App\Http\Requests\frontend\ApplicationForm\AdventureApplicationRequest;
use Illuminate\Support\Str;
use App\Models\Admin\ApplicationForm;
use App\Models\Admin\Master\Enterprise;
use App\Models\Admin\Master\Divisions;
use Illuminate\Database\QueryException;
use App\Models\District;
use Illuminate\Support\Facades\Log;
use Exception;

class AgricultureRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function report($id)
    {
        $Agriculture_Application = AgricultureRegistration::findOrFail($id);

        $division   = Divisions::find($Agriculture_Application->region_id);
        $district   = District::find($Agriculture_Application->district_id);
        $enterprise = Enterprise::find($Agriculture_Application->applicant_type_id);

        $region         = $division->name ?? '';
        $districtName   = $district->name ?? '';
        $applicant_type = $enterprise->name ?? '';

        // Full array (if you want to keep it)
        $Adventure_Application_data = [
            "id"                 => $Agriculture_Application->id,
            "email"              => $Agriculture_Application->email,
            "mobile"             => $Agriculture_Application->mobile,
            "applicant_type"     => $applicant_type,
            "applicant_name"     => $Agriculture_Application->applicant_name,
            "center_name"       => $Agriculture_Application->center_name,
            "applicant_address"  => $Agriculture_Application->applicant_address,
            "region"             => $region,
            "district"           => $districtName,
            "whatsapp"           => $Agriculture_Application->whatsapp,
            "adventure_category" => $Agriculture_Application->adventure_category,
            "activity_name"      => $Agriculture_Application->activity_name,
            "activity_location"  => $Agriculture_Application->activity_location,
            "pan_file"           => $Agriculture_Application->pan_file,
            "aadhar_file"        => $Agriculture_Application->aadhar_file,
            "status"             => $Agriculture_Application->status,
            "is_apply"           => $Agriculture_Application->is_apply,
            "submitted_at"       => $Agriculture_Application->submitted_at,
            "user_id"            => $Agriculture_Application->user_id,
            "registration_id"    => $Agriculture_Application->registration_id,
            "slug_id"            => $Agriculture_Application->slug_id,
            "application_form_id" => $Agriculture_Application->application_form_id,
            "created_at"         => $Agriculture_Application->created_at,
            "updated_at"         => $Agriculture_Application->updated_at,
        ];

        $application_form = ApplicationForm::find($Agriculture_Application->application_form_id);
        // ✅ Collect data
        $booleanFields = [
            'facility_day_trip',
            'facility_accommodation',
            'facility_recreational_service',
            'facility_play_area_children',
            'facility_adventure_games',
            'facility_rural_games',
            'facility_agricultural_camping',
            'facility_horticulture_product_sale',
            'activity_green_house',
            'activity_milk_business',
            'activity_fisheries',
            'activity_rop_vatika',
            'activity_animal_bird_rearing',
            'activity_nature_adventure_tourism',
            'activity_other',
        ];

        // ✅ Convert boolean (0 / 1 / null → Yes / No / —)
        $converted = [];
        foreach ($booleanFields as $field) {
            $value = $Agriculture_Application->$field;
            $converted[$field] = ($value === 1)
                ? 'Yes'
                : (($value === 0) ? 'No' : '—');
        }

        $data = [
            'Adventure_Application_data' => $Adventure_Application_data,
            'application_form'           => $application_form,

            'registration_id' => $Agriculture_Application->registration_id,
            'status'          => $Agriculture_Application->status,
            'applicant_name'  => $Agriculture_Application->applicant_name,
            'center_name'  => $Agriculture_Application->center_name,
            "applicant_type"     => $applicant_type,
            'mobile'          => $Agriculture_Application->mobile,
            'email'           => $Agriculture_Application->email,
            'submitted_at'    => $Agriculture_Application->submitted_at,
            'whatsapp'        => $Agriculture_Application->whatsapp,
            'applicant_address' => $Agriculture_Application->applicant_address,
            'company_name'    => $Agriculture_Application->company_name,
            'region'          => $region,
            'district'        => $districtName,


            'center_address' => $Agriculture_Application->center_address,
            'land_description' => $Agriculture_Application->land_description,
            'applicant_live_in_place' => $Agriculture_Application->applicant_live_in_place,
            'activity_other_text' => $Agriculture_Application->activity_other_text,
            'center_started_on' => $Agriculture_Application->center_started_on,



            'activity_name'   => $Agriculture_Application->activity_name,
            'activity_location'   => $Agriculture_Application->activity_location,
        ];

        $data = array_merge($data, $converted);
        $fileLabels = [
            'file_signature_stamp'         => 'Applicant Signature / Stamp',
            'file_land_documents'          => 'Land Documents',
            'file_registration_certificate'=> 'Registration Certificate',
            'file_authorization_letter'    => 'Authorization Letter',
            'file_pan_card'                => 'PAN Card',
            'file_aadhar_card'             => 'Aadhar Card',
            'file_registration_fee_challan'=> 'Registration Fee Challan',
            'file_electricity_bill'        => 'Electricity Bill',
            'file_food_security_licence'   => 'Food Security Licence',
            'file_building_permission'     => 'Building Permission',
            'file_declaration_form'        => 'Declaration Form',
            'file_zone_certificate'        => 'Zone Certificate',
        ];

        // make a file data array for Blade
        $uploadedFiles = [];

        foreach ($fileLabels as $key => $label) {
            $path = $Agriculture_Application->$key ?? null;

            $uploadedFiles[] = [
                'label' => $label,
                'path'  => $path,
                'exists' => !empty($path) && file_exists(public_path('storage/'.$path)),
            ];
        }
        // Attach it safely
        $data['uploadedFiles'] = $uploadedFiles;
        // dd($data);
        return view('frontend.Application.AgricultureApplication.reports', $data);
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'email'       => 'required|email',
                'mobile'      => 'required|string|max:15',
                'applicant_name' => 'required|string|max:255',
                'center_name' => 'required|string|max:255',
                'applicant_address' => 'required|string',
                'land_description'  => 'required|string',
                'file_signature_stamp' => 'required|file|max:5120',
                'file_land_documents'  => 'required|file|max:5120',
                'file_pan_card'        => 'required|file|max:5120',
                'file_aadhar_card'     => 'required|file|max:5120',
                'file_registration_fee_challan' => 'required|file|max:5120',
                'file_electricity_bill' => 'required|file|max:5120',
                'file_food_security_licence' => 'required|file|max:5120',
                'file_declaration_form' => 'required|file|max:5120',
            ]);

            $data = $request->only([
                'email',
                'mobile',
                'applicant_name',
                'center_name',
                'applicant_type_id',
                'applicant_address',
                'center_address',
                'region_id',
                'district_id',
                'land_description',
                'center_started_on',
            ]);

            $data['user_id']  = Auth::id();
            $data['status']   = 'submitted';
            $data['application_form_id'] = $request->id;
            $data['is_apply'] = true;
            $data['submitted_at'] = now();

            $boolFields = [
                'facility_day_trip',
                'facility_accommodation',
                'facility_recreational_service',
                'facility_play_area_children',
                'facility_adventure_games',
                'facility_rural_games',
                'facility_agricultural_camping',
                'facility_horticulture_product_sale',
                'activity_green_house',
                'activity_milk_business',
                'activity_fisheries',
                'activity_rop_vatika',
                'activity_animal_bird_rearing',
                'activity_nature_adventure_tourism',
                'activity_other',
            ];

            foreach ($boolFields as $field) {
                $data[$field] = $request->boolean($field);
            }

            $data['activity_other_text'] = $request->activity_other_text;
            $data['applicant_live_in_place'] = $request->applicant_live_in_place ?? 'yes';

            $nextId = (AgricultureRegistration::max('id') ?? 0) + 1;
            $data['registration_id'] = 'AGR-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            $data['slug_id'] = Str::uuid();


            $fileMap = [
                'file_signature_stamp'         => 'signature',
                'file_land_documents'          => 'land-docs',
                'file_registration_certificate' => 'registration-cert',
                'file_authorization_letter'    => 'authorization-letter',
                'file_pan_card'                => 'pan-card',
                'file_aadhar_card'             => 'aadhar-card',
                'file_registration_fee_challan' => 'fee-challan',
                'file_electricity_bill'        => 'electricity-bill',
                'file_food_security_licence'   => 'food-licence',
                'file_building_permission'     => 'building-permission',
                'file_declaration_form'        => 'declaration-form',
                'file_zone_certificate'        => 'zone-certificate',
            ];

            foreach ($fileMap as $field => $folder) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $ext  = $file->getClientOriginalExtension();
                    $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                    $filename = $base . '-' . time() . '-' . Str::random(6) . '.' . $ext;
                    $path = $file->storeAs('agriculture/' . $folder, $filename, 'public');
                    $data[$field] = $path;
                }
            }

            AgricultureRegistration::create($data);

            return redirect()
                ->route('applications.index')
                ->with('success', 'Agriculture Registration submitted successfully.');
        } catch (Exception $e) {
            Log::error('AgricultureRegistration Store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'Something went wrong while submitting your application. Please try again or contact support.');
        }
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
