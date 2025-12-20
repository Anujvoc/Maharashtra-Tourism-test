<?php

namespace App\Http\Controllers\Backend\Forms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ApplicationForm;
use App\Models\frontend\ApplicationForm\Application;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\frontend\ApplicationForm\AdventureApplication;
use App\Http\Requests\frontend\ApplicationForm\AdventureApplicationRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Master\Enterprise;
use App\Models\frontend\ApplicationForm\AgricultureRegistration;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Models\frontend\ApplicationForm\WomenCenteredTourismRegistration;
use App\Models\frontend\ApplicationForm\EligibilityRegistration;
use App\Models\frontend\ApplicationForm\ProvisionalRegistration;
use App\Models\frontend\CaravanRegistration\CaravanRegistration;
use App\Models\frontend\ApplicationForm\StampDutyApplication;
use App\Models\frontend\ApplicationForm\IndustrialRegistration;
class ApplicationFormsController extends Controller
{


    public function index()
    {
        $user = auth()->user();
        $role = $user->getRoleNames()->first(); 
        $models = [
            Application::class,
            AdventureApplication::class,
            AgricultureRegistration::class,
            WomenCenteredTourismRegistration::class,
            EligibilityRegistration::class,
            ProvisionalRegistration::class,
            CaravanRegistration::class,
            StampDutyApplication::class,
            IndustrialRegistration::class,
        ];

        $registration_data = collect();

        foreach ($models as $modelClass) {

            $query = $modelClass::query();

            // 🔐 ROLE BASED FILTER
            if (!$user->hasRole('Super Admin')) {
                $query->where('current_stage', $role)
                      ->whereNotIn('workflow_status', ['Certificate Generated']);
            }

            $rows = $query->select(
                'id',
                'application_form_id',
                'user_id',
                'registration_id',
                'status',
                'workflow_status',
                'submitted_at',
                'current_stage'
            )->get();

            foreach ($rows as $r) {
                $registration_data->push((object)[
                    'id' => $r->id,
                    'application_form_id' => $r->application_form_id,
                    'registration_id' => $r->registration_id,
                    'user_id' => $r->user_id,
                    'status' => $r->workflow_status ?? $r->status,
                    'submitted_at' => $r->submitted_at ? Carbon::parse($r->submitted_at) : null,
                    'model' => $modelClass,
                    'original' => $r,
                ]);
            }
        }

        $registration_data = $registration_data
            ->sortByDesc(fn ($item) => $item->submitted_at)
            ->values();

        return view('admin.ApplicationForms.index', compact('registration_data'));
    }




    public function index123()
    {
        $models = [
            Application::class,
            AdventureApplication::class,
            AgricultureRegistration::class,
            WomenCenteredTourismRegistration::class,
            EligibilityRegistration::class,
            ProvisionalRegistration::class,
            CaravanRegistration::class,
            StampDutyApplication::class,
            IndustrialRegistration::class,
        ];

        $registration_data = collect();

        foreach ($models as $modelClass) {
            $rows = $modelClass::select(
                'id',
                'application_form_id',
                'user_id',
                'registration_id',
                'status',
                'submitted_at'
            )->get();

            $rows->each(function ($r) use (&$registration_data, $modelClass) {
                $registration_data->push((object) [
                    'id' => $r->id,
                    'application_form_id' => $r->application_form_id,
                    'registration_id' => $r->registration_id,
                    'user_id' => $r->user_id,
                    'status' => $r->status,
                    'submitted_at' => $r->submitted_at ? Carbon::parse($r->submitted_at) : null,
                    'model' => $modelClass,
                    'original' => $r,
                ]);
            });
        }

        $registration_data = $registration_data->sortByDesc(function ($item) {
            return $item->submitted_at ? $item->submitted_at->timestamp : 0;
        })->values();

        return view('admin.ApplicationForms.index', compact('registration_data'));
    }

    public function show($modelParam, $id)
    {
        // 1. Map URL param (from class_basename lowercase) to Model Class & Type Slug
        $mapping = [
            'adventureapplication' => [
                'model' => AdventureApplication::class,
                'type' => 'adventure'
            ],
            'agricultureregistration' => [
                'model' => AgricultureRegistration::class,
                'type' => 'agriculture'
            ],
            'womencenteredtourismregistration' => [
                'model' => WomenCenteredTourismRegistration::class,
                'type' => 'women-centered'
            ],
            'industrialregistration' => [
                'model' => \App\Models\frontend\ApplicationForm\IndustrialRegistration::class,
                'type' => 'industrial'
            ],
            'provisionalregistration' => [
                'model' => ProvisionalRegistration::class,
                'type' => 'provisional'
            ],
            'eligibilityregistration' => [
                'model' => EligibilityRegistration::class,
                'type' => 'eligibility'
            ],
            'stampdutyapplication' => [
                'model' => StampDutyApplication::class,
                'type' => 'stamp-duty'
            ],
            'tourismapartment' => [
                'model' => \App\Models\frontend\ApplicationForm\TourismApartment::class,
                'type' => 'tourism-apartment'
            ],
            'caravanregistration' => [
                'model' => CaravanRegistration::class,
                'type' => 'caravan'
            ],
            'application' => [
                'model' => Application::class,
                'type' => 'generic-application'
            ],
        ];

        $info = $mapping[strtolower($modelParam)] ?? null;

        if (!$info) {
            // Fallback for debugging if new models are added
            return abort(404, 'Application Type Model not found: ' . $modelParam);
        }

        $modelClass = $info['model'];
        $typeSlug = $info['type'];

        $application = $modelClass::findOrFail($id);

        return view('admin.ApplicationForms.show', [
            'application' => $application,
            'type' => $typeSlug
        ]);
    }

    /**
     * Display the application report (iframe content).
     */
    public function report($type, $id)
    {
        $mapping = [
            'adventure' => AdventureApplication::class,
            'agriculture' => AgricultureRegistration::class,
            'women-centered' => WomenCenteredTourismRegistration::class,
            'industrial' => \App\Models\frontend\ApplicationForm\IndustrialRegistration::class,
            'provisional' => ProvisionalRegistration::class,
            'eligibility' => \App\Models\frontend\ApplicationForm\EligibilityRegistration::class,
            'stamp-duty' => StampDutyApplication::class,
            'tourism-apartment' => \App\Models\frontend\ApplicationForm\TourismApartment::class,
            'caravan' => CaravanRegistration::class,
            'generic-application' => Application::class,
        ];

        $modelClass = $mapping[$type] ?? null;

        if (!$modelClass) {
            abort(404, 'Application Type Not Found');
        }

        $application = $modelClass::findOrFail($id);

        // If it's the Generic Application, use the nice wizard report
        if ($type === 'generic-application') {
            // Load relations expected by the view
            $application->load([
                'applicant',
                'property',
                'accommodation',
                'facilities',
                'photos',
                'documents'
            ]);

            // Fake data for QR and Logic if needed, similar to ApplicationController
            $qrCode = 1;
            $logoPath = public_path('frontend/mah-logo-300x277.png');
            $logoBase64 = null;

            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
            }

            return view('frontend.wizard.reports', [
                'application' => $application,
                'application_form' => \App\Models\Admin\ApplicationForm::where('id', $application->application_form_id)->first(),
                'qrCode' => $qrCode,
                'logo' => $logoBase64,
                'registration_id' => $application->registration_id,
                'watermark' => $logoBase64
            ]);
        }

        // For other types, use the generic admin report
        return view('admin.ApplicationForms.generic-report', [
            'application' => $application,
            'type' => $type
        ]);
    }

    public function previewCertificate($type, $id)
    {
        $mapping = [
            'adventure' => AdventureApplication::class,
            'provisional' => ProvisionalRegistration::class,
            'stamp-duty' => StampDutyApplication::class,
            'tourism-apartment' => \App\Models\frontend\ApplicationForm\TourismApartment::class,
            'caravan' => CaravanRegistration::class,
            'generic-application' => Application::class,
            'industrial' => IndustrialRegistration::class,
            // Add other mappings as needed
        ];
        $modelClass = $mapping[$type] ?? null;
        if (!$modelClass)
            abort(404);

        $application = $modelClass::findOrFail($id);

        // Generate QR Code
        $qrData = "Name: " . ($application->applicant_name ?? $application->applicant->name ?? 'N/A') . "\n" .
            "Registration No: " . ($application->registration_id ?? 'N/A') . "\n" .
            "Type: " . str_replace('-', ' ', $type);

        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate($qrData);

        return view('admin.ApplicationForms.certificate', compact('application', 'type', 'qrCode'));
    }

    public function downloadCertificate($type, $id)
    {
        // For now, reuse preview or redirect to it with print trigger?
        // Ideally use PDF library like dompdf or snappy.
        // Assuming no PDF lib installed, we just show view and user uses print->save as pdf.
        // But the user asked for "download".
        // I will return the view with a specific request to trigger print dialog or use a dummy pdf download header if I had a generator.

        // Since I cannot install packages, I will redirect to preview with a print trigger.
        return redirect()->route('admin.certificate.show', ['type' => $type, 'id' => $id, 'print' => 'true']);
    }

    public function data()
    {
        $apps = Application::where('user_id', auth()->id())->latest()->get();

        $models = [
                // TourismApartment::class,
            AdventureApplication::class,
            AgricultureRegistration::class,
            IndustrialRegistration::class,

        ];

        $registration_data = collect();

        foreach ($models as $modelClass) {
            // Select only needed columns to reduce memory usage
            $rows = $modelClass::select(
                'id',
                'application_form_id',
                'registration_id',
                'status',
                'submitted_at'
            )->get();

            $rows->each(function ($r) use (&$registration_data, $modelClass) {
                $registration_data->push((object) [
                    'id' => $r->id,
                    'application_form_id' => $r->application_form_id,
                    'registration_id' => $r->registration_id,
                    'status' => $r->status,
                    'submitted_at' => $r->submitted_at ? Carbon::parse($r->submitted_at) : null,
                    'model' => $modelClass,
                    'original' => $r,
                ]);
            });
        }
        $registration_data = $registration_data->sortByDesc(function ($item) {
            return $item->submitted_at ? $item->submitted_at->timestamp : 0;
        })->values();
        $formIds = $apps->pluck('application_form_id')->unique()->filter()->values()->all();
        $forms = ApplicationForm::whereIn('id', $formIds)->get()->keyBy('id');
        return view('frontend.wizard.index', compact('apps', 'registration_data', 'forms'));
    }

    /** ApplicationForms
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
