<?php

namespace App\Http\Controllers\frontend\ApplicationForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\frontend\ApplicationForm\TourismApartment;
use App\Models\frontend\ApplicationForm\AdventureApplication;
use App\Http\Requests\frontend\ApplicationForm\AdventureApplicationRequest;
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


class AdventureApplicationController extends Controller
{

    public function index()
    {
        $applications = AdventureApplication::latest()->paginate(15);
        return view('adventure-applications.index', compact('applications'));
    }


    public function get_Region_District($id)
    {

        $division = Divisions::where('id', $id)->first();
        if (!$division) {
            return response()->json(['error' => 'Division not found'], 404);
        }
        $districtIds = json_decode($division->districts, true);

        if (!is_array($districtIds)) {
            return response()->json(['error' => 'Invalid district data'], 400);
        }

        $districts = District::whereIn('id', $districtIds)
            ->select('id', 'name')
            ->get();

        return response()->json($districts);
    }


    public function report($id)
    {
        $Adventure_Application = AdventureApplication::findOrFail($id);

        $division   = Divisions::find($Adventure_Application->region_id);
        $district   = District::find($Adventure_Application->district_id);
        $enterprise = Enterprise::find($Adventure_Application->applicant_type);

        $region         = $division->name ?? '';
        $districtName   = $district->name ?? '';
        $applicant_type = $enterprise->name ?? '';

        // Full array (if you want to keep it)
        $Adventure_Application_data = [
            "id"                 => $Adventure_Application->id,
            "email"              => $Adventure_Application->email,
            "mobile"             => $Adventure_Application->mobile,
            "applicant_type"     => $applicant_type,
            "applicant_name"     => $Adventure_Application->applicant_name,
            "company_name"       => $Adventure_Application->company_name,
            "applicant_address"  => $Adventure_Application->applicant_address,
            "region"             => $region,
            "district"           => $districtName,
            "whatsapp"           => $Adventure_Application->whatsapp,
            "adventure_category" => $Adventure_Application->adventure_category,
            "activity_name"      => $Adventure_Application->activity_name,
            "activity_location"  => $Adventure_Application->activity_location,
            "pan_file"           => $Adventure_Application->pan_file,
            "aadhar_file"        => $Adventure_Application->aadhar_file,
            "status"             => $Adventure_Application->status,
            "is_apply"           => $Adventure_Application->is_apply,
            "submitted_at"       => $Adventure_Application->submitted_at,
            "user_id"            => $Adventure_Application->user_id,
            "registration_id"    => $Adventure_Application->registration_id,
            "slug_id"            => $Adventure_Application->slug_id,
            "application_form_id"=> $Adventure_Application->application_form_id,
            "created_at"         => $Adventure_Application->created_at,
            "updated_at"         => $Adventure_Application->updated_at,
        ];

        $application_form = ApplicationForm::find($Adventure_Application->application_form_id);

        $data = [
            'Adventure_Application_data' => $Adventure_Application_data,
            'application_form'           => $application_form,

            'registration_id' => $Adventure_Application->registration_id,
            'status'          => $Adventure_Application->status,
            'applicant_name'  => $Adventure_Application->applicant_name,
            'mobile'          => $Adventure_Application->mobile,
            'email'           => $Adventure_Application->email,
            'submitted_at'    => $Adventure_Application->submitted_at,
            'whatsapp'        => $Adventure_Application->whatsapp,
            'applicant_address'=> $Adventure_Application->applicant_address,
            'company_name'    => $Adventure_Application->company_name,
            'region'          => $region,
            'district'        => $districtName,
            'adventure_category'=> $Adventure_Application->adventure_category,
            'activity_name'   => $Adventure_Application->activity_name,
            'activity_location'   => $Adventure_Application->activity_location,
        ];



        return view('frontend.Application.AdventureApplications.reports', $data);
    }




    public function store(AdventureApplicationRequest $request)
    {
        try {
            $data = $request->validated();

            // system fields
            $data['user_id'] = auth()->id();
            $data['status'] = $data['status'] ?? 'submitted';
            $data['is_apply'] = true;
            $data['application_form_id'] = $request->id;
            $data['submitted_at'] = now();
            $data['registration_id'] = 'adv-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            $data['slug_id'] = $data['slug_id'] ?? 'adv-' . Str::random(8);

            // Files - use storeAs to create friendly unique filenames
            if ($request->hasFile('pan_file')) {
                $file = $request->file('pan_file');
                $ext  = $file->getClientOriginalExtension();
                $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $filename = $base . '-' . time() . '-' . Str::random(6) . '.' . $ext;
                $path = $file->storeAs('documents/pan', $filename, 'public');
                $data['pan_file'] = $path;
            }

            if ($request->hasFile('aadhar_file')) {
                $file = $request->file('aadhar_file');
                $ext  = $file->getClientOriginalExtension();
                $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $filename = $base . '-' . time() . '-' . Str::random(6) . '.' . $ext;
                $path = $file->storeAs('documents/aadhar', $filename, 'public');
                $data['aadhar_file'] = $path;
            }

            $application = AdventureApplication::create($data);

            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'message' => 'Application saved', 'data' => $application], 200);
            }

            return redirect()->route('applications.index')->with('success', 'Application saved');
        } catch (QueryException $ex) {
            Log::error('DB Error saving adventure application: ' . $ex->getMessage());
            if ($request->ajax()) return response()->json(['status' => 'error', 'message' => 'Database error. Try later.'], 500);
            return back()->with('error', 'Database error. Try later.');
        } catch (\Throwable $ex) {
            Log::error('Error saving adventure application: ' . $ex->getMessage());
            if ($request->ajax()) return response()->json(['status' => 'error', 'message' => 'Server error. Try later.'], 500);
            return back()->with('error', 'Server error. Try later.');
        }
    }

    public function update(AdventureApplicationRequest $request, AdventureApplication $adventureApplication)
    {
        try {
            $data = $request->validated();

            $data['submitted_at'] = $adventureApplication->submitted_at ?? now();
            $data['slug_id'] = $data['slug_id'] ?? $adventureApplication->slug_id ?? 'adv-' . Str::random(8);

            // Replace files when provided (use storeAs); delete previous files if exist
            if ($request->hasFile('pan_file')) {
                // delete old
                if ($adventureApplication->pan_file && Storage::disk('public')->exists($adventureApplication->pan_file)) {
                    Storage::disk('public')->delete($adventureApplication->pan_file);
                }

                $file = $request->file('pan_file');
                $ext  = $file->getClientOriginalExtension();
                $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $filename = $base . '-' . time() . '-' . Str::random(6) . '.' . $ext;
                $path = $file->storeAs('documents/pan', $filename, 'public');
                $data['pan_file'] = $path;
            }

            if ($request->hasFile('aadhar_file')) {
                if ($adventureApplication->aadhar_file && Storage::disk('public')->exists($adventureApplication->aadhar_file)) {
                    Storage::disk('public')->delete($adventureApplication->aadhar_file);
                }

                $file = $request->file('aadhar_file');
                $ext  = $file->getClientOriginalExtension();
                $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $filename = $base . '-' . time() . '-' . Str::random(6) . '.' . $ext;
                $path = $file->storeAs('documents/aadhar', $filename, 'public');
                $data['aadhar_file'] = $path;
            }

            $adventureApplication->update($data);

            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'message' => 'Application updated', 'data' => $adventureApplication], 200);
            }

            return redirect()->route('frontend.adventure-applications.edit', $adventureApplication->id)->with('success', 'Application updated');
        } catch (QueryException $ex) {
            Log::error('DB Error updating adventure application: ' . $ex->getMessage());
            if ($request->ajax()) return response()->json(['status' => 'error', 'message' => 'Database error. Try later.'], 500);
            return back()->with('error', 'Database error. Try later.');
        } catch (\Throwable $ex) {
            Log::error('Error updating adventure application: ' . $ex->getMessage());
            if ($request->ajax()) return response()->json(['status' => 'error', 'message' => 'Server error. Try later.'], 500);
            return back()->with('error', 'Server error. Try later.');
        }
    }




    /**
     * Display the specified resource.
     */
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

    public function update123(AdventureApplicationRequest $request, AdventureApplication $adventureApplication)
    {
        $data = $request->validated();

        dd(123);

        if ($request->hasFile('pan_file')) {
            if ($adventureApplication->pan_file) {
                Storage::disk('public')->delete($adventureApplication->pan_file);
            }
            $data['pan_file'] = $request->file('pan_file')->store('documents/pan', 'public');
        }
        if ($request->hasFile('aadhar_file')) {
            if ($adventureApplication->aadhar_file) {
                Storage::disk('public')->delete($adventureApplication->aadhar_file);
            }
            $data['aadhar_file'] = $request->file('aadhar_file')->store('documents/aadhar', 'public');
        }

        $adventureApplication->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Application updated', 'data' => $adventureApplication], 200);
        }

        return redirect()->route('adventure-applications.index')->with('success', 'Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
