<?php

namespace App\Http\Controllers\frontend\ApplicationForm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\frontend\ApplicationForm\EligibilityRegistration;
use Illuminate\Support\Str;
use App\Models\Admin\ApplicationForm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Admin\Master\Divisions;
use App\Models\District;
use App\Models\Country;
class EligibilityRegistrationController extends Controller
{
    public function store(Request $request)
    {

        // return $request->all();
        // dd($request->all());
        try {
            $validated = $request->validate([
                'applicant_name'     => ['required','string','max:255','regex:/^[A-Za-z\s]+$/'],
                'provisional_number' => ['nullable','string','max:255'],
                'gst_number'         => ['nullable','string','max:15'],
                'project_description'=> ['required','string','min:10'],
                'region_id'   => ['required', 'integer', 'exists:divisions,id'],
                'district_id' => ['required', 'integer', 'exists:districts,id'],
                'commencement_date'  => ['nullable','date'],
                'operation_details'  => ['nullable','string','max:255'],
                'declaration_place'  => ['required','string','max:255','regex:/^[A-Za-z\s]+$/'],
                'declaration_date'   => ['required','date'],
                'signature_upload'   => ['required','file','mimes:jpg,jpeg,png,pdf','max:2048'],
            ],[
                'applicant_name.regex'    => 'Name may contain only letters and spaces.',
                'declaration_place.regex' => 'Place may contain only letters and spaces.',
            ]);

            DB::beginTransaction();

            // Signature upload
            $signaturePath = null;

            if ($request->hasFile('signature_upload')) {
                $file = $request->file('signature_upload');
                $filename = 'signature_' . time() . '.' . $file->getClientOriginalExtension();
                $signaturePath = $file->storeAs('eligibility/signatures', $filename, 'public');
            }


            // JSON fields
            $entrepreneurs  = $request->input('entrepreneurs', []);
            $costComponent  = $request->input('cost_component', []);
            $assetAge       = $request->input('asset_age', []);
            $ownership      = $request->input('ownership', []); // sab ownership[...][] checkboxes bhi isme

            // Enclosures (each key -> doc_no, issue_date, file)
            $enclosures = [];
            if ($request->has('enclosures')) {
                foreach ($request->enclosures as $key => $enc) {
                    $filePath = null;

                    if ($request->hasFile("enclosures.$key.file")) {
                        $file = $request->file("enclosures.$key.file");
                        $label    = $enclosure['label'] ?? $key;
                        $safeName = Str::slug($label, '_');

                        $filename = $safeName . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $filePath = $file->storeAs('eligibility/enclosures', $filename, 'public');
                    }
                    $enclosures[$key] = [
                        'label'      => $enc['label'] ?? null,
                        'doc_no'     => $enc['doc_no'] ?? null,
                        'issue_date' => $enc['issue_date'] ?? null,
                        'file_path'  => $filePath,
                    ];
                }
            }

            // Other docs
            $otherDocs = [];
            if ($request->has('other_docs')) {
                foreach ($request->other_docs as $idx => $doc) {
                    if (empty($doc['name']) && empty($doc['doc_no']) && !$request->hasFile("other_docs.$idx.file")) {
                        continue;
                    }

                    $filePath = null;
                    if ($request->hasFile("other_docs.$idx.file")) {
                        $filePath = $request->file("other_docs.$idx.file")
                            ->store('eligibility/other_docs', 'public');
                    }

                    $otherDocs[] = [
                        'name'          => $doc['name'] ?? null,
                        'doc_no'        => $doc['doc_no'] ?? null,
                        'issue_date'    => $doc['issue_date'] ?? null,
                        'validity_date' => $doc['validity_date'] ?? null,
                        'file_path'     => $filePath,
                    ];
                }
            }

            $appFormId      = $request->input('application_form_id');
            $registrationId = 'ELIG-' . strtoupper(Str::random(8));

            $registration = EligibilityRegistration::create([
                'applicant_name'      => $validated['applicant_name'],
                'provisional_number'  => $validated['provisional_number'] ?? null,
                'gst_number'          => $validated['gst_number'] ?? null,

                'entrepreneurs'       => $entrepreneurs,
                'project_description' => $validated['project_description'],

                'commencement_date'   => $validated['commencement_date'] ?? null,
                'operation_details'   => $validated['operation_details'] ?? null,

                'region_id'   => $validated['region_id'] ?? null,
                'district_id'   => $validated['district_id'] ?? null,

                'cost_component'      => $costComponent,
                'asset_age'           => $assetAge,
                'ownership'           => $ownership,

                'enclosures'          => $enclosures,
                'other_docs'          => $otherDocs,

                'signature_path'      => $signaturePath,
                'declaration_place'   => $validated['declaration_place'],
                'declaration_date'    => $validated['declaration_date'],

                'status'              => 'submitted',
                'is_apply'            => true,
                'submitted_at'        => Carbon::now(),

                'user_id'             => Auth::id(),
                'registration_id'     => $registrationId,
                'slug_id'             => (string) Str::uuid(),
                'application_form_id' => $appFormId,
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'status'       => 'success',
                    'message'      => 'Eligibility Application submitted successfully.',
                    // 'redirect_url' => route('eligibility-registrations.show', $registration->id),
                    'redirect_url' => route('applications.index'),
                ]);
            }

            return redirect()
                ->route('applications.index')
                ->with('success', 'Application submitted successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Something went wrong. Please try again.',
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function show(EligibilityRegistration $registration)
    {
        abort_unless($registration->user_id === Auth::id(), 403);
        $application_form = ApplicationForm::find($registration->application_form_id);
        $region = Divisions::find($registration->region_id);
        $district = District::find($registration->district_id);
        return view('frontend.Application.Eligibility.reports', compact('registration',
        'application_form',
        'region',
        'district'
    ));
    }
}
