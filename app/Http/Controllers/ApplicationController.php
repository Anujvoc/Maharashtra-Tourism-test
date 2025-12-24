<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\frontend\ApplicationForm\Application;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Models\frontend\ApplicationForm\TourismApartment;
use App\Models\frontend\ApplicationForm\AdventureApplication;
use App\Http\Requests\frontend\ApplicationForm\AdventureApplicationRequest;
use App\Models\Admin\ApplicationForm;
use App\Models\frontend\ApplicationForm\AgricultureRegistration;
use App\Models\frontend\ApplicationForm\EligibilityRegistration;
use App\Models\frontend\ApplicationForm\WomenCenteredTourismRegistration;
use App\Models\frontend\ApplicationForm\ProvisionalRegistration;
use App\Models\frontend\CaravanRegistration\CaravanRegistration;
use App\Models\frontend\ApplicationForm\StampDutyApplication;
use App\Models\frontend\ApplicationForm\TouristVillaRegistration;
use App\Models\frontend\ApplicationForm\IndustrialRegistration;

class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        $app = Application::create([
            'user_id' => auth()->id(),
            'slug_id' => (string) Str::ulid(),
            'status' => 'draft',
            'is_apply' => false,
            'current_step' => 1,
        ]);

        return redirect()->route('wizard.show', [$app, 'step' => 1]);
    }

    public function index()
    {
        $userId = auth()->id();
        $apps = Application::where('user_id', $userId)
            ->where('application_form_id', '!=', 1)
            ->latest()
            ->get();

        // dd($apps);

        // Lazy Sync for Existing Data (Temporary Fix)
        foreach ($apps as $app) {
            if ($app->workflow_status !== 'Certificate Generated') {
                // Check if child is approved
                $child = null;
                // Add checks for other models if needed
                if ($app->provisionalRegistration && $app->provisionalRegistration->workflow_status === 'Certificate Generated') {
                    $child = $app->provisionalRegistration;
                } elseif ($app->touristVillaRegistration && $app->touristVillaRegistration->workflow_status === 'Certificate Generated') {
                    $child = $app->touristVillaRegistration;
                } elseif ($app->tourismApartment && $app->tourismApartment->workflow_status === 'Certificate Generated') {
                    $child = $app->tourismApartment;
                } elseif ($app->adventureApplication && $app->adventureApplication->workflow_status === 'Certificate Generated') {
                    $child = $app->adventureApplication;
                } elseif ($app->agricultureRegistration && $app->agricultureRegistration->workflow_status === 'Certificate Generated') {
                    $child = $app->agricultureRegistration;
                } elseif ($app->womenCenteredTourismRegistration && $app->womenCenteredTourismRegistration->workflow_status === 'Certificate Generated') {
                    $child = $app->womenCenteredTourismRegistration;
                } elseif ($app->caravanRegistration && $app->caravanRegistration->workflow_status === 'Certificate Generated') {
                    $child = $app->caravanRegistration;
                } elseif ($app->stampDutyApplication && $app->stampDutyApplication->workflow_status === 'Certificate Generated') {
                    $child = $app->stampDutyApplication;
                } elseif ($app->eligibilityRegistration && $app->eligibilityRegistration->workflow_status === 'Certificate Generated') {
                    $child = $app->eligibilityRegistration;
                } elseif ($app->industrialRegistration && $app->industrialRegistration->workflow_status === 'Certificate Generated') {
                    $child = $app->industrialRegistration;
                }
                // ... check others ...

                if ($child) {
                    $app->workflow_status = 'Certificate Generated';
                    $app->status = 'approved';
                    $app->save();
                }
            }
        }



        $models = [
                //new models add here
            IndustrialRegistration::class,
                //till here
            StampDutyApplication::class,
            ProvisionalRegistration::class,
            EligibilityRegistration::class,
            AdventureApplication::class,
            AgricultureRegistration::class,
            WomenCenteredTourismRegistration::class,
            CaravanRegistration::class,
        ];

        $registration_data = collect();

        foreach ($models as $modelClass) {

            // Provisional ka data extra fields ke saath
            if ($modelClass === ProvisionalRegistration::class) {
                $rows = $modelClass::where('user_id', $userId)
                    ->select(
                        'id',
                        'application_id',
                        'application_form_id',
                        'registration_id',
                        'status',
                        'submitted_at',
                        'current_step',
                        'workflow_status'
                    )
                    ->get();
            } else {
                // Baaki models ke liye common fields
                $rows = $modelClass::where('user_id', $userId)
                    ->select(
                        'id',
                        'application_form_id',
                        'registration_id',
                        'status',
                        'submitted_at',
                        'current_stage',
                        'workflow_status'
                    )
                    ->get();
            }

            $rows->each(function ($r) use (&$registration_data, $modelClass) {
                $registration_data->push((object) [
                    'id' => $r->id,                        // registration model id
                    'application_id' => $r->application_id ?? null,    // sirf provisional me hoga
                    'application_form_id' => $r->application_form_id,
                    'registration_id' => $r->registration_id,
                    'status' => $r->status,
                    'submitted_at' => $r->submitted_at ? Carbon::parse($r->submitted_at) : null,
                    'current_step' => $r->current_step ?? null,      // sirf provisional me hoga
                    'workflow_status' => $r->workflow_status ?? null,
                    'model' => $modelClass,
                    'has_remarks' => ($r->public_remarks_count ?? 0) > 0,
                ]);
            });
        }

        // latest submitted first

        // dd($registration_data);
        $registration_data = $registration_data
            ->sortByDesc(function ($item) {
                return $item->submitted_at ? $item->submitted_at->timestamp : 0;
            })
            ->values();

        // Form names ke liye
        $formIds = $apps->pluck('application_form_id')->unique()->filter()->values()->all();
        $forms = ApplicationForm::whereIn('id', $formIds)->get()->keyBy('id');

        return view('frontend.wizard.index', compact('apps', 'registration_data', 'forms'));
    }

    public function index1()
    {
        $userId = auth()->id();
        $apps = Application::where('user_id', auth()->id())->latest()->get();

        $models = [
            ProvisionalRegistration::class,
            AdventureApplication::class,
            AgricultureRegistration::class,
            WomenCenteredTourismRegistration::class,
            CaravanRegistration::class,

        ];

        $registration_data = collect();

        foreach ($models as $modelClass) {
            // Select only needed columns to reduce memory usage


            $rows = $modelClass::where('user_id', $userId)
                ->select('id', 'application_form_id', 'registration_id', 'status', 'submitted_at', 'current_stage', 'workflow_status')
                ->get();


            // $rows = $modelClass::where('user_id', auth()->id())
            // ->select('id', 'application_form_id', 'registration_id', 'status', 'submitted_at')
            // ->get();

            $rows->each(function ($r) use (&$registration_data, $modelClass) {
                $registration_data->push((object) [
                    'id' => $r->id,
                    'application_form_id' => $r->application_form_id,
                    'registration_id' => $r->registration_id,
                    'status' => $r->status,
                    'current_stage' => $r->current_stage,
                    'workflow_status' => $r->workflow_status,
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

    public function generateReport(Request $request, $id)
    {
        $application = Application::with([
            'applicant',
            'property',
            'accommodation',
            'facilities',
            'photos',
            'documents'
        ])->findOrFail($id);



        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $qrData = [
            'id' => $application->id,
            'registration_id' => $application->registration_id,
            'applicant' => $application->applicant->name ?? 'N/A',
            'status' => $application->status,
            'submitted_at' => $application->submitted_at?->format('Y-m-d')
        ];

        // $qrCode = base64_encode(QrCode::format('png')
        //     ->size(150)
        //     ->generate(json_encode($qrData)));
        $qrCode = 1;

        $logoPath = public_path('frontend/mah-logo-300x277.png');
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        $data = [
            'application' => $application,
            'qrCode' => $qrCode,
            'logo' => $logoBase64,
            'registration_id' => $application->registration_id,
            'watermark' => $logoBase64
        ];

        // dd($application->application_form_id);

        $data['application_form'] = ApplicationForm::where('id', $application->application_form_id)->first();

        if ($request->has('download')) {
            return $this->downloadPDF($data);
        }

        return view('frontend.wizard.reports', $data);
        // return view('frontend.wizad.reports.application', $data);
    }
    private function downloadPDF($data)
    {
        return view('frontend.reports.application-pdf', $data);
    }
    public function reports()
    {
        $apps = Application::where('user_id', auth()->id())
            ->with(['applicant', 'property'])
            ->latest()
            ->get();

        $stats = [
            'total' => $apps->count(),
            'submitted' => $apps->where('status', 'submitted')->count(),
            'draft' => $apps->where('status', 'draft')->count(),
            'approved' => $apps->where('status', 'approved')->count(),
            'rejected' => $apps->where('status', 'rejected')->count(),
        ];

        return view('frontend.reports.index', compact('apps', 'stats'));
    }

    public function getRemarks($type, $id)
    {
        $map = [
            'adventure' => AdventureApplication::class,
            'agriculture' => AgricultureRegistration::class,
            'women-centered' => WomenCenteredTourismRegistration::class,
            'industrial' => \App\Models\frontend\ApplicationForm\IndustrialRegistration::class,
            'provisional' => ProvisionalRegistration::class,
            'eligibility' => EligibilityRegistration::class,
            'stamp-duty' => StampDutyApplication::class,
            'tourism-apartment' => TourismApartment::class,
            'caravan' => CaravanRegistration::class,
            'generic-application' => Application::class,
        ];

        $modelClass = $map[$type] ?? null;

        if (!$modelClass) {
            return response()->json(['error' => 'Invalid application type'], 400);
        }

        // ✅ sirf usi user ka record uthega jiska login hai
        $application = $modelClass::where('id', $id)
            ->where('user_id', auth()->id())
            ->with([
                'workflowLogs' => function ($q) {
                    $q->where('is_public', true)->orderBy('created_at', 'desc');
                },
                'verificationDocuments'
            ])
            ->firstOrFail();

        $logs = $application->workflowLogs->map(function ($log) {
            return [
                'stage' => $log->stage,
                'status' => $log->status,
                'remark' => $log->remark,
                'created_at' => $log->created_at->format('d M Y, h:i A'),
            ];
        });

        // Fetch Rejected or Re-uploaded Documents
        $rejectedDocs = collect();
        if ($application->verificationDocuments) {
            $rejectedDocs = $application->verificationDocuments->filter(function ($doc) {
                $approvals = $doc->role_approvals ?? [];

                // Check if re-uploaded
                if (isset($approvals['_meta']['is_reuploaded']) && $approvals['_meta']['is_reuploaded']) {
                    return true;
                }

                // Check if ANY role has rejected this document
                foreach ($approvals as $role => $data) {
                    if (isset($data['status']) && $data['status'] === 'Rejected') {
                        return true;
                    }
                }
                return false;
            })->map(function ($doc) {
                $approvals = $doc->role_approvals ?? [];
                $isReuploaded = isset($approvals['_meta']['is_reuploaded']) && $approvals['_meta']['is_reuploaded'];

                // Find rejection details
                $rejectionData = collect($approvals)->map(function ($data, $role) {
                    return array_merge($data, ['role' => $role]);
                })->where('status', 'Rejected')->first();

                $remark = 'Rejected';
                if ($rejectionData) {
                    $roleName = ucwords(str_replace(['_', '-'], ' ', $rejectionData['role']));

                    if ($isReuploaded) {
                        $remark = "Pending Approval ({$roleName})";
                    } else {
                        // Adjust role names if needed (e.g. 'Dy Director' from 'dy_director')
                        $remark = "Rejected by {$roleName}: " . ($rejectionData['remark'] ?? '');
                    }
                } elseif ($isReuploaded) {
                    $remark = 'Pending Approval';
                }

                return [
                    'id' => $doc->id,
                    'label' => $doc->document_label,
                    'file_path' => $doc->file_path,
                    'remark' => $remark,
                    'is_reuploaded' => $isReuploaded
                ];
            })->values();
        }

        return response()->json([
            'logs' => $logs,
            'rejected_docs' => $rejectedDocs
        ]);
    }

    public function getRemarks123($type, $id)
    {
        // Simple mapping (could be refactored to a shared helper/config)
        $map = [
            'adventure' => AdventureApplication::class,
            'agriculture' => AgricultureRegistration::class,
            'women-centered' => WomenCenteredTourismRegistration::class,
            'industrial' => \App\Models\frontend\ApplicationForm\IndustrialRegistration::class,
            'provisional' => ProvisionalRegistration::class,
            'eligibility' => EligibilityRegistration::class,
            'stamp-duty' => StampDutyApplication::class,
            'tourism-apartment' => TourismApartment::class,
            'caravan' => CaravanRegistration::class,
            'generic-application' => Application::class,
        ];

        $modelClass = $map[$type] ?? null;

        if (!$modelClass) {
            return response()->json(['error' => 'Invalid application type'], 400);
        }

        $application = $modelClass::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // Eager load workflow logs
        $logs = $application->workflowLogs()
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'stage' => $log->stage,
                    'status' => $log->status,
                    'remark' => $log->remark,
                    'created_at' => $log->created_at->format('d M Y, h:i A'),
                ];
            });

        return response()->json(['logs' => $logs]);
    }
    public function downloadCertificate($type, $id)
    {
        $map = [
            'adventure' => AdventureApplication::class,
            'agriculture' => AgricultureRegistration::class,
            'women-centered' => WomenCenteredTourismRegistration::class,
            'industrial' => \App\Models\frontend\ApplicationForm\IndustrialRegistration::class,
            'provisional' => ProvisionalRegistration::class,
            'eligibility' => EligibilityRegistration::class,
            'stamp-duty' => StampDutyApplication::class,
            'tourism-apartment' => TourismApartment::class,
            'tourist-villa' => TouristVillaRegistration::class,
            'caravan' => CaravanRegistration::class,
            'generic-application' => Application::class,
        ];

        $modelClass = $map[$type] ?? null;

        if (!$modelClass) {
            abort(404, 'Invalid Application Type');
        }

        // Fetch application specifically for the logged-in user
        $application = $modelClass::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // Check if certificate is actually generated
        if ($application->workflow_status !== 'Certificate Generated') {
            // Optional: Allow download if it's approved? User requirement says "final complete hone ke bad" which implies Certificate Generated.
            // But for safety let's allow if status is 'Certificate Generated'.
            // Or fail silent/redirect back.
            // Verify user intent: "complete hone ke bad".
            // Let's stick to status check.
            if ($application->workflow_status !== 'Certificate Generated') {
                abort(403, 'Certificate not yet generated.');
            }
        }

        // Generate QR Code
        $qrData = "Name: " . ($application->applicant_name ?? $application->applicant->name ?? 'N/A') . "\n" .
            "Registration No: " . ($application->registration_id ?? 'N/A') . "\n" .
            "Type: " . str_replace('-', ' ', $type);

        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate($qrData);

        // Reuse the Admin Certificate View (It's generic)
        // Pass 'print' => true to auto-trigger print dialog
        return view('admin.ApplicationForms.certificate', compact('application', 'type', 'qrCode'));
    }
}
