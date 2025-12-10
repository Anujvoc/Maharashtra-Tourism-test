<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\frontend\ApplicationForm\Application;
use App\Models\frontend\ApplicationForm\ApplicantDetail;
use App\Models\frontend\ApplicationForm\PropertyDetail;
use App\Models\frontend\ApplicationForm\Accommodation;
use App\Models\frontend\ApplicationForm\Facility;
use App\Models\frontend\ApplicationForm\PhotosSignature;
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

class WizardController extends Controller
{
    public function show(Application $application, int $step)
    {

        abort_if($application->user_id !== auth()->id(), 403);

        // prevent jumping too far ahead
        if ($step > $application->current_step + 1) {
            return redirect()->route('wizard.show', [$application, 'step' => $application->current_step]);
        }

        $application_form = ApplicationForm::where('id', $application->application_form_id)
            ->first();

        $Districts = District::where('state_id', 14)
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();

        $business_type =  Enterprise::orderBy('name', 'asc')->get();

        $States =  State::where('id', 14)
            ->first();
        // dd($application_form->name);

        $view = match ($step) {
            1 => 'frontend.wizard.step1',
            2 => 'frontend.wizard.step2',
            3 => 'frontend.wizard.step3',
            4 => 'frontend.wizard.step4',
            5 => 'frontend.wizard.step5',
            6 => 'frontend.wizard.step6',
            7 => 'frontend.wizard.review',
            default => abort(404),
        };

        return view($view, compact('application', 'application_form', 'States', 'Districts', 'business_type'));
    }

    /** Bump current_step if needed */
    protected function advance(Application $app, int $nextStep): void
    {
        if ($nextStep > $app->current_step) {
            $app->forceFill(['current_step' => $nextStep])->save();
        }
    }

    /** Step 1: Applicant */
    public function saveApplicant123(Application $application, Request $r)
    {

        $data =
            $r->validate([
                'name' => 'required|string|max:120',
                'phone' => 'required|string|max:15|regex:/^[6-9][0-9]{9}$/',
                'email' => 'required|email',
                'business_name' => 'required|string|max:120',
                'business_type' => 'required|string',
                'state'    => ['required', 'string', 'max:100'],
                'district' => ['required', 'string', 'max:120'],
                'pan' => 'required|string|max:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/',
                'business_pan' => 'nullable|string|max:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/',
                'aadhaar' => 'required|string|max:12|regex:/^\d{12}$/',
                'udyam' => 'nullable|string|max:19',
                'ownership_proof_type' => 'required|string',
                'ownership_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'is_property_rented' => 'required|boolean',
                'operator_name' => 'nullable|required_if:is_property_rented,1|string|max:120',
                'rental_agreement' => 'nullable|required_if:is_property_rented,1|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);

        $payload = array_merge($data, [
            'user_id'        => auth()->id(),
            'application_id' => $application->id,
            'is_property_rented' => (bool)($data['is_property_rented'] ?? false),
        ]);

        // handle files
        foreach (['rental_agreement', 'ownership_proof'] as $fileKey) {
            if ($r->hasFile($fileKey)) {
                $payload[$fileKey] = $r->file($fileKey)->store("applications/{$application->id}/applicant", 'public');
            }
        }

        $application->applicant()
            ->updateOrCreate(['application_id' => $application->id], $payload);

        $this->advance($application, 2);

        return redirect()->route('wizard.show', [$application, 'step' => 2])
            ->with('saved', true);
    }
    public function saveApplicant(Application $application, Request $r)
    {
        // dd($r->all());
        // Load existing applicant (may be null on create)
        $applicant = $application->applicant;
        $existingOwnership = optional($applicant)->ownership_proof;
        $existingRental = optional($applicant)->rental_agreement;

        // Normalize boolean from request (handles '1','0','true','false' etc.)
        $isPropertyRented = filter_var($r->input('is_property_rented', false), FILTER_VALIDATE_BOOLEAN);

        // Build dynamic server-side rules
        $rules = [
            'name' => 'required|string|max:120',
            'phone' => ['required', 'string', 'max:15', 'regex:/^[6-9][0-9]{9}$/'],
            'email' => 'required|email',
            'business_name' => 'required|string|max:120',
            // business_type is an id from enterprises table
            'business_type' => 'required|exists:enterprises,id',
            'state'    => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:120'],
            'pan' => ['required', 'string', 'max:10', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/'],
            'business_pan' => ['nullable', 'string', 'max:10', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/'],
            'aadhaar' => ['required', 'string', 'max:12', 'regex:/^\d{12}$/'],
            'udyam' => 'nullable|string|max:19',
            'ownership_proof_type' => 'required|string',
            // 'is_property_rented' validated below
        ];

        // ownership_proof required only if not present already
        $rules['ownership_proof'] = $existingOwnership
            ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
            : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';

        // is_property_rented: required boolean
        $rules['is_property_rented'] = 'required|boolean';

        // operator_name: required only when rented
        $rules['operator_name'] = $isPropertyRented ? 'required|string|max:120' : 'nullable|string|max:120';

        // rental_agreement: required only if rented AND no existing rental file
        if ($isPropertyRented && !$existingRental) {
            $rules['rental_agreement'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        } else {
            $rules['rental_agreement'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        // Validate request
        $data = $r->validate($rules);

        // Wrap DB actions in transaction
        DB::beginTransaction();
        try {
            // Prepare payload for applicant
            $payload = [
                'user_id' => auth()->id(),
                'application_id' => $application->id,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'business_name' => $data['business_name'],
                'business_type' => $data['business_type'], // stored as enterprise id
                'state' => $data['state'],
                'district' => $data['district'],
                'pan' => $data['pan'],
                'business_pan' => $data['business_pan'] ?? null,
                'aadhaar' => $data['aadhaar'],
                'udyam' => $data['udyam'] ?? null,
                'ownership_proof_type' => $data['ownership_proof_type'],
                'is_property_rented' => (bool) $data['is_property_rented'],
                'operator_name' => $data['operator_name'] ?? null,
                // 'ownership_proof' and 'rental_agreement' appended below if uploaded
            ];

            // Handle files (ownership_proof, rental_agreement) using storeAs
            foreach (['ownership_proof', 'rental_agreement'] as $fileKey) {
                if ($r->hasFile($fileKey)) {
                    $file = $r->file($fileKey);
                    $ext = $file->getClientOriginalExtension();

                    // Build custom filename: e.g. ownership_proof_{application_id}.{ext}
                    $filename = "{$fileKey}_{$application->id}." . $ext;
                    $folder = "applications/{$application->id}/applicant";

                    // Delete old file if exists
                    $old = optional($applicant)->{$fileKey} ?? null;
                    if ($old && Storage::disk('public')->exists($old)) {
                        Storage::disk('public')->delete($old);
                    }

                    // store using storeAs on public disk
                    $path = $file->storeAs($folder, $filename, 'public');

                    // include in payload
                    $payload[$fileKey] = $path;
                }
                // if no new file uploaded, keep existing DB value by not overwriting in payload
            }

            // Create or update applicant (keeps same id if exists)
            $application->applicant()->updateOrCreate(
                ['application_id' => $application->id],
                $payload
            );

            // Advance the wizard step (existing method)
            $this->advance($application, 2);

            DB::commit();

            return redirect()->route('wizard.show', [$application, 'step' => 2])
                ->with('saved', true);
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log error if you want: \Log::error($e);
            return redirect()->back()
                ->withInput()
                ->withErrors(['unexpected' => 'An error occurred while saving. Please try again.']);
        }
    }

    /** Step 2: Property */
    public function saveProperty(Application $application, Request $r)
    {
        // dd($r->all());
        // Load existing property (may be null)
        $property = $application->property;
        $existingAddress = optional($property)->address_proof;

        // Normalize boolean
        $isOperational = filter_var($r->input('is_operational', false), FILTER_VALIDATE_BOOLEAN);

        // Server-side validation (aligned with client)
        $rules = [
            'property_name'     => 'required|string|max:160',
            // Accept coordinates string or URL => keep as string on server
            'geo_link'          => 'nullable|string|max:255',
            'address'           => 'required|string|max:1000',
            'address_proof_type' => ['required', 'string', 'in:Latest Electricity Bill,Water Bill,Other'],
            'address_proof'     => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
            'total_area_sqft'   => 'nullable|integer|min:0',
            'mahabooking_reg_no' => 'nullable|string|max:80',
            'is_operational'    => 'required|boolean',
            'operational_since' => $isOperational ? 'nullable|integer|min:1900|max:2030' : 'nullable|integer',
            'guests_till_march' => $isOperational ? 'nullable|integer|min:0' : 'nullable|integer',
            'district_id'       => 'required|exists:districts,id',
        ];

        $data = $r->validate($rules);

        DB::beginTransaction();
        try {
            $payload = [
                'user_id'        => auth()->id(),
                'application_id' => $application->id,
                'property_name'  => $data['property_name'],
                'geo_link'       => $data['geo_link'] ?? null,
                'address'        => $data['address'],
                'address_proof_type' => $data['address_proof_type'],
                'total_area_sqft' => $data['total_area_sqft'] ?? null,
                'mahabooking_reg_no' => $data['mahabooking_reg_no'] ?? null,
                'is_operational' => (bool) $data['is_operational'],
                'operational_since' => $data['operational_since'] ?? null,
                'guests_till_march' => $data['guests_till_march'] ?? null,
                'district_id' => $data['district_id'],
            ];

            // handle address_proof file with storeAs and delete old if replacing
            if ($r->hasFile('address_proof')) {
                $file = $r->file('address_proof');
                $ext = $file->getClientOriginalExtension();

                $dir = "applications/{$application->id}/property";
                $filename = 'address_proof_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $ext;

                // delete old if exists
                if ($existingAddress && Storage::disk('public')->exists($existingAddress)) {
                    Storage::disk('public')->delete($existingAddress);
                }

                $path = $file->storeAs($dir, $filename, 'public');
                $payload['address_proof'] = $path;
            } // else keep existing by not overriding

            // updateOrCreate property relation (assumes relation property() exists)
            $application->property()->updateOrCreate(
                ['application_id' => $application->id],
                $payload
            );

            // advance wizard
            $this->advance($application, 3);

            DB::commit();

            return redirect()->route('wizard.show', [$application, 'step' => 3])->with('saved', true);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('saveProperty error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->withErrors(['unexpected' => 'An error occurred while saving. Please try again.']);
        }
    }


    public function saveProperty123(Application $application, Request $r)
    {


        $data = $r->validate([
            'property_name'     => 'required|string|max:160',
            'geo_link'          => 'nullable|url|max:255',
            'address'           => 'required|string|max:1000',
            'address_proof_type'        => ['required', 'string', 'in:Latest Electricity Bill,Water Bill,Other'],
            'address_proof'     => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:2048',
            'total_area_sqft'   => 'nullable|integer|min:0',
            'mahabooking_reg_no' => 'nullable|string|max:80',
            'is_operational'    => 'nullable|boolean',
            'operational_since' => 'nullable|date_format:Y',
            'guests_till_march' => 'nullable|integer|min:0',
        ]);

        $payload = array_merge($data, [
            'user_id'        => auth()->id(),
            'application_id' => $application->id,
            'is_operational' => (bool)($data['is_operational'] ?? false),
        ]);


        if ($r->hasFile('address_proof')) {
            $file = $r->file('address_proof');
            $dir = "applications/{$application->id}/property";
            $filename = 'address_proof_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs($dir, $filename, 'public');
            $payload['address_proof'] = $dir . '/' . $filename;
        }


        $application->property()
            ->updateOrCreate(['application_id' => $application->id], $payload);

        $this->advance($application, 3);

        return redirect()->route('wizard.show', [$application, 'step' => 3])->with('saved', true);
    }

    public function saveAccommodation(Application $application, Request $r)
    {
        $data = $r->validate([
            'flats_count'     => 'required|integer|min:1',
            'flat_types'      => ['sometimes', 'array'],
            'flat_types.*'    => ['nullable', 'string', 'max:200'],
            'has_dustbins'    => 'required|boolean',
            'attached_toilet' => 'required|boolean',
            'road_access'     => 'required|boolean',
            'food_on_request' => 'required|boolean',
            'payment_upi'     => 'required|boolean',
        ]);

        // collect & normalize types (flat_types[] includes quick box)
        $types = $r->input('flat_types', []);
        if (!is_array($types)) $types = [$types];

        // trim, remove blanks, unique
        $types = array_values(array_unique(array_filter(array_map(function ($v) {
            return is_string($v) ? trim($v) : '';
        }, $types))));

        if (count($types) === 0) {
            return back()
                ->withErrors(['flat_types' => 'Please add at least one flat/room type.'])
                ->withInput();
        }

        $payload = [
            'user_id'          => auth()->id(),
            'application_id'   => $application->id,
            'flats_count'      => (int) $data['flats_count'],
            // store as array (requires JSON column + $casts in model)
            'flat_types'       => $types,
            'has_dustbins'     => (bool) $data['has_dustbins'],
            'attached_toilet'  => (bool) $data['attached_toilet'],
            'road_access'      => (bool) $data['road_access'],
            'food_on_request'  => (bool) $data['food_on_request'],
            'payment_upi'      => (bool) $data['payment_upi'],
        ];

        DB::beginTransaction();
        try {
            $application->accommodation()->updateOrCreate(
                ['application_id' => $application->id],
                $payload
            );

            $this->advance($application, 4);

            DB::commit();

            return redirect()->route('wizard.show', [$application, 'step' => 4])->with('saved', true);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('saveAccommodation error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->withErrors(['unexpected' => 'An error occurred while saving. Please try again.']);
        }
    }

    public function saveAccommodation123(Application $application, Request $r)
    {
        dd($r->all());
        $data = $r->validate([
            'flats_count'     => 'required|integer|min:1',
            'flat_types'      => ['sometimes', 'array'],
            'flat_types.*'    => ['nullable', 'string', 'max:200'],
            'has_dustbins'    => 'required|boolean',
            'attached_toilet'    => 'required|boolean',
            'road_access'     => 'required|boolean',
            'food_on_request' => 'required|boolean',
            'payment_upi'     => 'required|boolean',
        ]);

        $types = $r->input('flat_types', []);
        if ((!is_array($types) || count($types) === 0) && $r->filled('ftype')) {
            $types = [$r->input('ftype')];
        }

        $types = array_values(array_unique(array_filter(array_map(function ($v) {
            return is_string($v) ? trim($v) : '';
        }, is_array($types) ? $types : []))));

        if (count($types) === 0) {
            return back()
                ->withErrors(['flat_types' => 'Please add at least one flat/room type.'])
                ->withInput();
        }

        $payload = [
            'user_id'          => auth()->id(),
            'application_id'   => $application->id,
            'flats_count'      => (int) $data['flats_count'],
            'flat_types'      => $types,
            // 'flat_types'       => $data['flat_types'] ? json_decode($data['flat_types'], true) : [],
            'has_dustbins'     => (bool)($data['has_dustbins'] ?? false),
            'attached_toilet'     => (bool)($data['has_dustbins'] ?? false),
            'road_access'      => (bool)($data['road_access'] ?? false),
            'food_on_request'  => (bool)($data['food_on_request'] ?? false),
            'payment_upi'      => (bool)($data['payment_upi'] ?? false),
        ];

        $application->accommodation()
            ->updateOrCreate(['application_id' => $application->id], $payload);

        $this->advance($application, 4);

        return redirect()->route('wizard.show', [$application, 'step' => 4])->with('saved', true);
    }

    /** Step 4: Facilities */
    public function saveFacilities(Application $application, Request $r)
    {
        // dd($r->all());
        $data = $r->validate([
            'facilities'   => ['required', 'array', 'min:1'],
            'facilities.*' => ['integer', 'exists:tourismfacilities,id'],
            'gras_paid'    => ['required', 'in:0,1'],
        ]);

        $facilityIds = collect($data['facilities'])
            ->map(fn($id) => (int) $id)
            ->filter()->unique()->values()->all();

        if (empty($facilityIds)) {
            return back()->withErrors(['facilities' => 'Please select at least one facility.'])
                ->withInput();
        }

        $application->facilities()
            ->updateOrCreate(
                ['application_id' => $application->id],   // lookup
                [
                    'facilities'     => $facilityIds,     // JSON
                    'gras_paid'      => (int) $data['gras_paid'],
                    'user_id'        => auth()->id(),
                ]
            );

        $this->advance($application, 5);

        return redirect()
            ->route('wizard.show', [$application, 'step' => 5])
            ->with('saved', true);
    }


    /** Step 5: Photo & Signature (signature file only; photos uploaded via UploadController) */
    public function savePhotos(Application $application, Request $r)
    {
        // Laravel validation: size in KB (50 => 50KB, 200 => 200KB)
        $data = $r->validate([
            'applicant_signature' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:50'],
            'applicant_image'     => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:200'],
        ], [
            'applicant_signature.max'   => 'Signature must be less than 50KB',
            'applicant_image.max'       => 'Photo must be less than 200KB',
            'applicant_signature.mimes' => 'Signature must be JPG/JPEG/PNG format',
            'applicant_image.mimes'     => 'Photo must be JPG/JPEG/PNG format',
        ]);

        DB::beginTransaction();

        try {
            $existing = optional($application->photos);

            $payload = [
                'user_id'        => auth()->id(),
                'application_id' => $application->id,
            ];

            /**-----------------------------------------
             *  APPLICANT SIGNATURE
             ------------------------------------------*/
            if ($r->hasFile('applicant_signature')) {

                $file = $r->file('applicant_signature');
                $ext  = $file->getClientOriginalExtension();

                $dir  = "applications/{$application->id}/photos";
                $filename = "signature_{$application->id}.".$ext;

                $path = $file->storeAs($dir, $filename, 'public');

                if ($existing && $existing->applicant_signature &&
                    Storage::disk('public')->exists($existing->applicant_signature) &&
                    $existing->applicant_signature !== $path) {
                    Storage::disk('public')->delete($existing->applicant_signature);
                }

                $payload['applicant_signature'] = $path;

            } elseif ($r->filled('existing_applicant_signature')) {

                $payload['applicant_signature'] = $r->existing_applicant_signature;
            }



            /**-----------------------------------------
             *  APPLICANT PHOTO
             ------------------------------------------*/
            if ($r->hasFile('applicant_image')) {

                $file = $r->file('applicant_image');
                $ext  = $file->getClientOriginalExtension();

                $dir  = "applications/{$application->id}/photos";
                $filename = "photo_{$application->id}.".$ext;

                $path = $file->storeAs($dir, $filename, 'public');

                if ($existing && $existing->applicant_image &&
                    Storage::disk('public')->exists($existing->applicant_image) &&
                    $existing->applicant_image !== $path) {
                    Storage::disk('public')->delete($existing->applicant_image);
                }

                $payload['applicant_image'] = $path;

            } elseif ($r->filled('existing_applicant_image')) {

                $payload['applicant_image'] = $r->existing_applicant_image;
            }


            /**-----------------------------------------
             *  CREATE/UPDATE
             ------------------------------------------*/
            $application->photos()
                ->updateOrCreate(
                    ['application_id' => $application->id],
                    $payload
                );

            $this->advance($application, 6);

            DB::commit();

            return redirect()
                ->route('wizard.show', [$application, 'step' => 6])
                ->with('saved', true);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error("PHOTO SAVE ERROR: ".$e->getMessage(), [
                'app_id' => $application->id,
            ]);

            return back()
                ->withInput()
                ->withErrors(['unexpected' => 'Something went wrong while saving photos. Please try again.', $e->getMessage()]);
        }
    }
    public function savePhotos123(Application $application, Request $r)
    {
        $data = $r->validate([
            'applicant_signature' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png',
                'max:50' // 50KB
            ],
            'applicant_image' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png',
                'max:200' // 200KB
            ],
        ], [
            'applicant_signature.max' => 'Signature must be less than 50KB',
            'applicant_image.max' => 'Photo must be less than 200KB',
            'applicant_signature.mimes' => 'Signature must be JPG/JPEG/PNG format',
            'applicant_image.mimes' => 'Photo must be JPG/JPEG/PNG format',
        ]);

        $payload = [
            'user_id'        => auth()->id(),
            'application_id' => $application->id,
        ];

        // Keep existing files if no new files uploaded
        if ($r->hasFile('applicant_signature')) {
            $payload['applicant_signature'] = $r->file('applicant_signature')
                ->store("applications/{$application->id}/photos", 'public');
        } elseif ($r->has('existing_applicant_signature')) {
            $payload['applicant_signature'] = $r->existing_applicant_signature;
        }

        if ($r->hasFile('applicant_image')) {
            $payload['applicant_image'] = $r->file('applicant_image')
                ->store("applications/{$application->id}/photos", 'public');
        } elseif ($r->has('existing_applicant_image')) {
            $payload['applicant_image'] = $r->existing_applicant_image;
        }

        $application->photos()
            ->updateOrCreate(['application_id' => $application->id], $payload);

        $this->advance($application, 6);

        return redirect()->route('wizard.show', [$application, 'step' => 6])->with('saved', true);
    }




    /** Step 6: Enclosures (acts as step-complete; uploads are AJAX via UploadController) */
    public function saveEnclosures123(Application $application, Request $r)
    {

        $application->enclosures()
            ->updateOrCreate(
                ['application_id' => $application->id],
                ['user_id' => auth()->id(), 'meta' => $application->documents()->pluck('category')->unique()->values()]
            );


        $this->advance($application, 7);

        return redirect()->route('wizard.show', [$application, 'step' => 7])->with('saved', true);
    }

    public function saveEnclosures(Application $application, Request $r)
    {
        // Save/refresh the enclosures meta (keeps current behaviour)
        $application->enclosures()
            ->updateOrCreate(
                ['application_id' => $application->id],
                ['user_id' => auth()->id(), 'meta' => $application->documents()->pluck('category')->unique()->values()]
            );

        // server-side required docs check (run here so user can't proceed to step 7)
        $requiredCats = [
            'aadhar','pan','business_reg','ownership',
            'property_photos','character','society_noc',
            'building_perm','gras_copy','undertaking'
        ];

        // what categories we have in DB (unique)
        $have = $application->documents()->pluck('category')->toArray();

        // special check for property photos: require at least 5 items
        $missing = array_diff($requiredCats, $have);

        // property_photos might be present but fewer than 5
        $propertyPhotosCount = $application->documents()
            ->where('category', 'property_photos')
            ->count();

        if (in_array('property_photos', $have) && $propertyPhotosCount < 5) {
            // ensure it's reported missing or flagged
            $missing[] = 'property_photos';
        }

        if (!empty($missing)) {
            // attach friendly messages and redirect back to Step 6
            // Persist missing list so blade can highlight specific rows
            return redirect()
                ->route('wizard.show', [$application, 'step' => 6])
                ->withErrors(['documents' => 'Please upload the required documents before proceeding.'])
                ->with('missing_docs', array_values($missing));
        }

        // all good — advance to step 7
        $this->advance($application, 7);

        return redirect()->route('wizard.show', [$application, 'step' => 7])->with('saved', true);
    }

    /** Final Submit: lock and generate registration_id */
    public function submit(Application $application, Request $r)
    {
        // dd($r->all());
        // completeness guard (server side). keep here as final safety net.
        foreach (['applicant', 'property', 'accommodation', 'facilities', 'photos', 'enclosures'] as $rel) {
            abort_if(!$application->$rel, 422, ucfirst($rel) . ' step incomplete.');
        }

        $requiredCats = ['aadhar', 'pan', 'business_reg', 'ownership', 'property_photos', 'character', 'society_noc', 'building_perm', 'gras_copy', 'undertaking'];
        $have = $application->documents()->pluck('category')->unique()->toArray();

        // ensure property_photos >= 5
        $propertyPhotosCount = $application->documents()->where('category', 'property_photos')->count();
        if (!in_array('property_photos', $have) || $propertyPhotosCount < 5) {
            abort(422, 'Property photos are incomplete (minimum 5 required).');
        }

        foreach ($requiredCats as $rc) {
            abort_if(!in_array($rc, $have), 422, "Missing required document: $rc");
        }

        $application->forceFill([
            'status'        => 'submitted',
            'is_apply'      => true,
            'registration_id' => 'TV' . now()->format('Ymd') . strtoupper(Str::random(6)),
            'submitted_at'  => now(),
        ])->save();

        return redirect()->route('applications.index')->with('success', 'Application submitted successfully.');
    }

    /** Final Submit: lock and generate registration_id */
    public function submit123(Application $application, Request $r)
    {
        dd($r->all());
        // completeness guard
        foreach (['applicant', 'property', 'accommodation', 'facilities', 'photos', 'enclosures'] as $rel) {
            abort_if(!$application->$rel, 422, ucfirst($rel) . ' step incomplete.');
        }

        // (Optional) enforce at least some required documents exist
        $requiredCats = ['aadhar', 'pan', 'business_reg', 'ownership', 'property_photos', 'character', 'society_noc', 'building_perm', 'gras_copy', 'undertaking'];
        $have = $application->documents()->pluck('category')->unique()->toArray();
        foreach ($requiredCats as $rc) {
            abort_if(!in_array($rc, $have), 422, "Missing required document: $rc");
        }

        $application->forceFill([
            'status'        => 'submitted',
            'is_apply'      => true,
            'registration_id' => 'TV' . now()->format('Ymd') . strtoupper(Str::random(6)),
            'submitted_at'  => now(),
        ])->save();

        return redirect()->route('applications.index')->with('success', 'Application submitted successfully.');
    }
}
