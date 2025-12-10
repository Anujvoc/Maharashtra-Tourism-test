<?php

namespace App\Http\Controllers\frontend\CaravanRegistration;

use App\Http\Controllers\Controller;
use App\Models\Admin\Master\Caravan\CaravanAmenity;
use App\Models\Admin\master\Caravan\CaravanOptionalFeature;
use App\Models\Admin\Master\Caravan\CaravanType;
use App\Models\Admin\master\Divisions;
use App\Models\Admin\master\Enterprise;
use App\Models\District;
use App\Models\frontend\CaravanRegistration\CaravanRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;




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
        $validated['amenities'] = $request->amenities;
        $validated['optional_features'] = $request->optional_features;
        $validated['user_id'] = auth()->id();
        $validated['slug_id'] = uniqid('caravan_');
        $validated['submitted_at'] = now();


        $caravan = CaravanRegistration::create($validated);

        $application_form_id = $request->id ?? '';
        $registration_id = 'CRV-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);

        $caravan->update([
            'application_form_id' => $application_form_id ?? '',
            'registration_id' => $registration_id ?? '',
        ]);

        return redirect()->route('applications.index')->with('success', 'Caravan Registration Submitted Successfully.');
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
