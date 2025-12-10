<?php

namespace App\Http\Controllers\frontend\ApplicationForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\frontend\ApplicationForm\TouristVillaRegistration;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class TouristVillaRegistrationController extends Controller
{
    public function index()
    {
        $items = TouristVillaRegistration::latest()->paginate(20);
        return view('villa_registrations.index', compact('items'));
    }

    public function create()
    {
        // If you want to reuse your existing big Blade, return that view here
        return view('villa_registrations.create');
    }

    public function store(Request $request)
    {
        $v = $this->validateData($request);

        $payload = [
            'application_id'          => $request->input('application_id'),
            'user_id'                 => Auth::id(),

            'applicant_name'          => $v['applicantName'],
            'applicant_phone'         => $v['applicantPhone'],
            'applicant_email'         => $v['applicantEmail'],
            'business_name'           => $v['businessName'],
            'business_type'           => $v['businessType'],
            'pan_number'              => $v['panNumber'],
            'business_pan_number'     => $v['businessPanNumber']    ?? null,
            'aadhar_number'           => $v['aadharNumber'],
            'udyam_aadhar_number'     => $v['udyamAadharNumber']    ?? null,
            'ownership_proof'         => $v['ownershipProof'],
            'property_rented'         => Str::lower($v['propertyRented']) === 'yes',
            'operator_name'           => $v['operatorName']         ?? null,

            // B) Property
            'property_name'           => $v['propertyName'],
            'property_address'        => $v['propertyAddress'],
            'address_proof'           => $v['addressProof'],
            'property_coordinates'    => $v['propertyCoordinates'],
            'property_operational'    => Str::lower($v['propertyOperational']) === 'yes',
            'operational_year'        => $v['operationalYear']      ?? null,
            'guests_hosted'           => $v['guestsHosted']         ?? null,
            'total_area'              => $v['totalArea'],
            'mahabooking_number'      => $v['mahabookingNumber']    ?? null,

            // C) Accommodation
            'number_of_rooms'         => $v['numberOfRooms'],
            'room_area'               => $v['roomArea'],
            'attached_toilet'         => Str::lower($v['attachedToilet']) === 'yes',
            'dustbins'                => Str::lower($v['dustbins']) === 'yes',
            'road_access'             => Str::lower($v['roadAccess']) === 'yes',
            'food_provided'           => Str::lower($v['foodProvided']) === 'yes',
            'payment_options'         => Str::lower($v['paymentOptions']) === 'yes',

            // D) Facilities
            'facilities'              => $request->input('facilities', []),

            // E) GRAS
            'application_fees'        => Str::lower($v['applicationFees']) === 'yes',
        ];

        // File upload (custom filename)
        if ($request->hasFile('rentalAgreement')) {
            $file     = $request->file('rentalAgreement');
            $ext      = $file->getClientOriginalExtension();
            $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeBase = Str::slug($original);
            $stamp    = now()->format('YmdHis');
            $filename = "agreement-{$safeBase}-{$stamp}.{$ext}";

            $path = $file->storeAs('villa/rental-agreements', $filename, 'public');
            $payload['rental_agreement_path'] = $path;
        }

        $item = TouristVillaRegistration::create($payload);

        return redirect()
            ->route('frontend.application-forms.index')
            ->with('success', 'Application submitted successfully.');
    }

    public function show(TouristVillaRegistration $villa_registration)
    {
        return view('villa_registrations.show', ['item' => $villa_registration]);
    }

    public function edit(TouristVillaRegistration $villa_registration)
    {
        return view('villa_registrations.edit', ['item' => $villa_registration]);
    }

    public function update(Request $request, TouristVillaRegistration $villa_registration)
    {
        $data = $this->validateData($request, updating: true);

        $yn = fn($key) => Str::lower($request->input($key)) === 'yes';

        $data['property_rented']      = $yn('propertyRented');
        $data['property_operational'] = $yn('propertyOperational');
        $data['attached_toilet']      = $yn('attachedToilet');
        $data['dustbins']             = $yn('dustbins');
        $data['road_access']          = $yn('roadAccess');
        $data['food_provided']        = $yn('foodProvided');
        $data['payment_options']      = $yn('paymentOptions');
        $data['application_fees']     = $yn('applicationFees');

        $data['facilities'] = $request->input('facilities', []);

        if ($request->hasFile('rentalAgreement')) {
            $data['rental_agreement_path'] = $request->file('rentalAgreement')
                ->store('villa/rental-agreements', 'public');
        }

        $villa_registration->update($data);

        return redirect()
            ->route('villa-registrations.show', $villa_registration)
            ->with('success', 'Application updated.');
    }

    public function destroy(TouristVillaRegistration $villa_registration)
    {
        $villa_registration->delete();
        return redirect()->route('villa-registrations.index')->with('success', 'Deleted.');
    }

    /**
     * Central validation that matches your jQuery rules (server-side).
     */
    private function validateData(Request $request, bool $updating = false): array
    {
        $currentYear = (int) date('Y');

        // When propertyRented = Yes, operatorName + rentalAgreement required
        $propertyRented = Str::lower($request->input('propertyRented')) === 'yes';
        $propertyOperational = Str::lower($request->input('propertyOperational')) === 'yes';

        return $request->validate([
            // hidden
            'application_id' => ['nullable', 'integer'],

            // A) Applicant
            'applicantName'       => ['required', 'string', 'min:2', 'max:120', 'regex:/^[A-Za-z\s.\'-]+$/'],
            'applicantPhone'      => ['required', 'regex:/^[6-9]\d{9}$/'],
            'applicantEmail'      => ['required', 'email', 'max:120'],
            'businessName'        => ['required', 'string', 'min:2', 'max:120', 'regex:/^[A-Za-z\s.\'-]+$/'],
            'businessType'        => ['required', Rule::in(['Proprietorship', 'Partnership', 'Pvt Ltd', 'LLP', 'Public Ltd', 'Co-operative', 'Society', 'Trust', 'SHG', 'JFMC', 'Other'])],
            'panNumber'           => ['required', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/'],
            'businessPanNumber'   => ['nullable', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/'],
            'aadharNumber'        => ['required', 'regex:/^\d{12}$/'],
            'udyamAadharNumber'   => ['nullable', 'regex:/^[A-Za-z0-9\-_\/]{6,25}$/'],
            'ownershipProof'      => ['required', Rule::in(['7/12 document', 'Property Tax Receipts', 'Property Card', 'Other'])],
            'propertyRented'      => ['required', Rule::in(['Yes', 'No'])],
            'operatorName'        => [$propertyRented ? 'nullable' : 'nullable', 'string', 'min:2', 'max:120', 'regex:/^[A-Za-z\s.\'-]+$/'],
            'rentalAgreement'     => [$propertyRented ? 'nullable' : 'nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],

            // B) Property
            'propertyName'        => ['required', 'string', 'min:2', 'max:120'],
            'propertyAddress'     => ['required', 'string', 'min:5', 'max:500'],
            'addressProof'        => ['required', Rule::in(['Latest Electricity Bill', 'Water Bill', 'Other'])],
            'propertyCoordinates' => ['required', 'string', 'max:255'],
            'propertyOperational' => ['required', Rule::in(['Yes', 'No'])],
            'operationalYear'     => [$propertyOperational ? 'required' : 'nullable', 'integer', 'min:1900', 'max:' . ($currentYear + 5)],
            'guestsHosted'        => [$propertyOperational ? 'required' : 'nullable', 'integer', 'min:0', 'max:1000000'],
            'totalArea'           => ['required', 'integer', 'min:1', 'max:10000000'],
            'mahabookingNumber'   => ['nullable', 'string', 'min:4', 'max:40', 'regex:/^[A-Za-z0-9\-_\/]+$/'],

            // C) Accommodation
            'numberOfRooms'       => ['required', 'integer', 'min:1', 'max:500'],
            'roomArea'            => ['required', 'integer', 'min:1', 'max:100000'],
            'attachedToilet'      => ['required', Rule::in(['Yes', 'No'])],
            'dustbins'            => ['required', Rule::in(['Yes', 'No'])],
            'roadAccess'          => ['required', Rule::in(['Yes', 'No'])],
            'foodProvided'        => ['required', Rule::in(['Yes', 'No'])],
            'paymentOptions'      => ['required', Rule::in(['Yes', 'No'])],

            // D) Facilities (array of known keys; keep loose)
            'facilities'          => ['nullable', 'array'],
            'facilities.*'        => ['string'],

            // E) GRAS
            'applicationFees'     => ['required', Rule::in(['Yes', 'No'])],

            // Step 5 checkbox is front-end only; no need to persist
            'declaration'         => ['nullable'],
        ], [], [

            'applicantName' => 'applicant name',
            'panNumber'     => 'PAN',
            'aadharNumber'  => 'Aadhaar',
        ]);
    }
}
