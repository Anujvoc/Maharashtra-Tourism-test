<?php

namespace App\Http\Controllers\frontend\CaravanRegistration;

use App\Http\Controllers\Controller;
use App\Models\Admin\master\Caravan\CaravanAmenity;
use App\Models\Admin\master\Caravan\CaravanOptionalFeature;
use App\Models\Admin\master\Caravan\CaravanType;
use App\Models\Admin\master\Divisions;
use App\Models\Admin\master\Enterprise;
use App\Models\District;
use App\Models\frontend\CaravanRegistration\CaravanRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Admin\ApplicationForm;
use App\Models\frontend\Api\ApplicationMovement;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class CaravanRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $caravandata = CaravanRegistration::all();
        return view("frontend.CaravanRegistration.index", compact('caravandata'));
    }

     public function CaravanTourismForm()
    {
    
           $application_form = ApplicationForm::where('is_active', 1)
        ->where('slug','caravan-tourism-policy-registration')
        ->first();
        if(!$application_form){
            return response()->json([
                'status' => false,
                'message' => 'No Available Application Forms.'
            ], 400);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Caravan Tourism Policy Registration Forms.',
                'data' => $application_form,
            ]);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view("frontend.CaravanRegistration.create");
        $regions = Divisions::select('id', 'name')->get(); // adjust column names as needed
        $enterprises = Enterprise::select('id', 'name')->get(); // enterprises
        $caravanTypes = CaravanType::all(); // caravan types
        $amenities = CaravanAmenity::all(); // caravan amenity
        $optionalFeatures = CaravanOptionalFeature::all(); // caravan optional feature

        return view('frontend.CaravanRegistration.create', compact('regions', 'enterprises', 'caravanTypes', 'amenities', 'optionalFeatures'));
    }

    public function get_Region_District($id)
    {

        $division = Divisions::where('id', $id)->first();
        if (! $division) {
            return response()->json(['error' => 'Division not found'], 404);
        }
        $districtIds = json_decode($division->districts, true);

        if (! is_array($districtIds)) {
            return response()->json(['error' => 'Invalid district data'], 400);
        }

        $districts = District::whereIn('id', $districtIds)
            ->select('id', 'name')
            ->get();

        return response()->json($districts);
    }

    public function getDistricts($region_id)
    {
        return District::where('region_id', $region_id)->get();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    //    dd($request->all());
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
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|max:10|regex:/^[6-9][0-9]{9}$/',
            'applicant_name' => 'required|string|max:255',
            'address' => 'required|string',
            'region_id' => 'required|integer',
            'district_id' => 'required|integer',
            'applicant_type' => 'required|integer',
            'emergency_contact' => 'required|string|max:15',
            'caravan_type_id' => 'required|integer',
            'prior_experience' => 'nullable|string',
            'vehicle_reg_no' => 'required|string',
            'capacity' => 'nullable|integer',
            'beds' => 'nullable|integer',
            'engine_no' => 'nullable|string',
            'chassis_no' => 'nullable|string',
            'amenities' => $request->is('api/*') ? 'nullable|string' : 'nullable|array',
            'optional_features' => $request->is('api/*') ? 'nullable|string' : 'nullable|array',
            'routes' => 'required|string',

            // Files
            'registration_fee_challan' => 'required|file',
            'vehicle_reg_card' => 'required|file',
            'vehicle_insurance' => 'required|file',
            'declaration_form' => 'required|file',
            'aadhar_card' => 'required|file',
            'pan_card' => 'required|file',
            'vehicle_purchase_copy' => 'required|file',
            'company_proof' => 'required|file',
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

        // Handle file uploads
        foreach (
            [
                'registration_fee_challan',
                'vehicle_reg_card',
                'vehicle_insurance',
                'declaration_form',
                'aadhar_card',
                'pan_card',
                'vehicle_purchase_copy',
                'company_proof',
            ] as $file
        ) {
            if ($request->hasFile($file)) {
                $validated[$file] = $request->file($file)->store('caravan_docs', 'public');
            }
        }

        // Save Amenities + Optional Features JSON
        // $validated['amenities'] = $request->amenities;
        // $validated['optional_features'] = $request->optional_features;
        if ($request->is('api/*')) {
        if ($request->amenities) {
            $validated['amenities'] = collect(explode(',', str_replace('"', '', $request->amenities)))
                ->map(fn($val) => (int) trim($val))
                ->filter()
                ->toArray();
        }

        if ($request->optional_features) {
            $validated['optional_features'] = collect(explode(',', str_replace('"', '', $request->optional_features)))
                ->map(fn($val) => (int) trim($val))
                ->filter()
                ->toArray();
        }}

        $validated['user_id'] = $UserID;
        $validated['slug_id'] = uniqid('caravan_');
        $validated['submitted_at'] = now();


        $caravan = CaravanRegistration::create($validated);

        $application_form_id = $request->id ?? '';
        $registration_id = 'CRV-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);

        $caravan->update([
            'application_form_id' => $application_form_id ?? '',
            'registration_id' => $registration_id ?? '',
            'is_maitri' => $is_maitri ?? 0,
        ]);

        $movement = ApplicationMovement::create([
            'application_id' => $caravan->id,
            'desk_number' => 1,
            'officer_name' => 'Clerk',
            'action' => 'Submitted',
            'action_datetime' => now(),
            'remarks'     => 'submitted'
        ]);

          DB::commit();

         if ($request->is('api/*')) {
            return response()->json([
            'status' => true,
            'message' => 'Your registration has been submitted successfully!',
            'application_id' => $registration_id ?? null,
            ]);
        }

        return redirect()->route('applications.index')->with('success', 'Caravan Registration Submitted Successfully.');
        } catch (Exception $e) {
            Log::error('AgricultureRegistration Store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            if ($request->is('api/*')) {
            return response()->json([
                    'status' => false,
                    'message' => 'There was an error submitting your registration',
                    'errors' => $e->getMessage()
                ], 422);
            }
            return back()
                ->withInput()
                ->with('error', 'Something went wrong while submitting your application. Please try again or contact support.');
        }
    }

    protected function generateUniqueRegistrationId($prefix = 'MV')
    {
    do {
        $id = strtoupper($prefix . '-' . Str::upper(Str::random(8)));
    } while (User::where('registration_id', $id)->exists());
    
    return $id;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data = CaravanRegistration::find($id);
        return view("frontend.CaravanRegistration.reports", compact('data'));
    }
    //9waWgnCpLPkRqX8z51Ub3YddYKBq0TYEDhtQ2GpJ.jpg

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CaravanRegistration $caravanRegistration)
    {
        $regions = Divisions::select('id', 'name')->get(); // adjust column names as needed
        $enterprises = Enterprise::select('id', 'name')->get(); // enterprises
        $caravanTypes = CaravanType::all(); // caravan types
        $amenities = CaravanAmenity::all(); // caravan amenity
        $optionalFeatures = CaravanOptionalFeature::all(); // caravan optional feature
        return view("frontend.CaravanRegistration.edit", compact('caravanRegistration', 'regions', 'enterprises', 'caravanTypes', 'amenities', 'optionalFeatures'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $caravan = CaravanRegistration::findOrFail($id);

        $validated = $request->validate([
            'email' => 'required|email',
            'mobile' => 'required|string|max:15',
            'applicant_name' => 'required|string|max:255',
            'address' => 'required|string',
            'region_id' => 'required|integer',
            'district_id' => 'required|integer',
            'applicant_type' => 'required|integer',
            'emergency_contact' => 'required|string|max:15',
            'caravan_type_id' => 'required|integer',
            'prior_experience' => 'nullable|string',
            'vehicle_reg_no' => 'required|string',
            'capacity' => 'nullable|integer',
            'beds' => 'nullable|integer',
            'engine_no' => 'nullable|string',
            'chassis_no' => 'nullable|string',
            'amenities' => 'nullable|array',
            'optional_features' => 'nullable|array',
            'routes' => 'required|string',
            'registration_fee_challan' => 'nullable|file',
            'vehicle_reg_card' => 'nullable|file',
            'vehicle_insurance' => 'nullable|file',
            'declaration_form' => 'nullable|file',
            'aadhar_card' => 'nullable|file',
            'pan_card' => 'nullable|file',
            'vehicle_purchase_copy' => 'nullable|file',
            'company_proof' => 'nullable|file',
        ]);

        $fileFields = [
            'registration_fee_challan',
            'vehicle_reg_card',
            'vehicle_insurance',
            'declaration_form',
            'aadhar_card',
            'pan_card',
            'vehicle_purchase_copy',
            'company_proof',
        ];

        // foreach ($fileFields as $file) {
        //     if ($request->hasFile($file)) {
        //         if ($caravan->$file && \Storage::disk('public')->exists($caravan->$file)) {
        //             \Storage::disk('public')->delete($caravan->$file);
        //         }
        //         $validated[$file] = $request->file($file)->store('caravan_docs', 'public');
        //     } else {
        //         $validated[$file] = $caravan->$file;
        //     }
        // }
        $fileFields = [
            'registration_fee_challan',
            'vehicle_reg_card',
            'vehicle_insurance',
            'declaration_form',
            'aadhar_card',
            'pan_card',
            'vehicle_purchase_copy',
            'company_proof',
        ];

        foreach ($fileFields as $file) {

            if ($request->hasFile($file)) {

                if ($caravan->$file && Storage::disk('public')->exists($caravan->$file)) {
                    Storage::disk('public')->delete($caravan->$file);
                }

                $validated[$file] = $request->file($file)->store('caravan_docs', 'public');
            } else {
                // keep old path but clean it
                $validated[$file] = ltrim(str_replace('public/', '', $caravan->$file), '/');
            }
        }



        $validated['amenities'] = $request->amenities ?? [];
        $validated['optional_features'] = $request->optional_features ?? [];

        $caravan->update($validated);

        return redirect()->route('frontend.caravan-registrations.index')
            ->with('success', 'Caravan Registration Updated Successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $caravan = CaravanRegistration::findOrFail($id);

        $fileFields = [
            'registration_fee_challan',
            'vehicle_reg_card',
            'vehicle_insurance',
            'declaration_form',
            'aadhar_card',
            'pan_card',
            'vehicle_purchase_copy',
            'company_proof',
        ];

        foreach ($fileFields as $file) {
            $path = $caravan->$file;
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $caravan->delete();

        return redirect()->route('frontend.caravan-registrations.index')
            ->with('success', 'Caravan Registration Deleted Successfully.');
    }
}
