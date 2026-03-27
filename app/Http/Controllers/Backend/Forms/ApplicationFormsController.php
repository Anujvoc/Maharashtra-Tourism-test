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
use App\Models\Admin\master\Enterprise;
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
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->getRoleNames()->first();
        $viewStatus = $request->input('view_status', 'pending'); // Default to pending if not set

        $models = [
            Application::class,
            \App\Models\frontend\ApplicationForm\AdventureApplication::class,
            \App\Models\frontend\ApplicationForm\AgricultureRegistration::class,
            \App\Models\frontend\ApplicationForm\WomenCenteredTourismRegistration::class,
            \App\Models\frontend\ApplicationForm\EligibilityRegistration::class,
            \App\Models\frontend\ApplicationForm\ProvisionalRegistration::class,
            \App\Models\frontend\CaravanRegistration\CaravanRegistration::class,
            \App\Models\frontend\ApplicationForm\StampDutyApplication::class,
            \App\Models\frontend\ApplicationForm\IndustrialRegistration::class,
        ];

        $registration_data = collect();

        foreach ($models as $modelClass) {
            $query = $modelClass::query();

            $query->where('is_apply', true);
            // --- Status Filtering Logic ---
            if ($user->hasRole('Super Admin')) {
                if ($viewStatus == 'rejected') {
                    $query->where('workflow_status', 'Rejected');
                } elseif ($viewStatus == 'approved') {
                    $query->where('status', 'approved');
                }
            } else {
                // Role Based Filtering
                if ($viewStatus == 'pending') {
                    $query->where('current_stage', $role)
                        ->whereNotIn('workflow_status', ['Rejected', 'Returned', 'Clarification', 'Certificate Generated', 'Certificate Issued', 'Site Visit Requested', 'Certificate Pending']);
                } elseif ($viewStatus == 'returned') {
                    $query->where('current_stage', $role)->where('workflow_status', 'Returned');
                } elseif ($viewStatus == 'clarification_list') {
                    // if ($role == 'Clerk') {
                    //     $query->where('current_stage', $role)->where('workflow_status', 'Returned');
                    // }
                    if ($role == 'Clerk') {
                        // dd(123);
                        $query->where('current_stage', $role)->where('workflow_status', 'Clarification');
                    }
                } elseif ($viewStatus == 'rejected') {
                    $query->where('workflow_status', 'Rejected');
                } elseif ($viewStatus == 'approved') {
                    $query->where('status', 'approved');
                } elseif ($viewStatus == 'certificate_pending') {
                    if ($role == 'Asst Director') {
                        $query->where('workflow_status', 'Certificate Pending');
                    }
                } elseif ($viewStatus == 'site_visit_requested') {
                    $query->where('workflow_status', 'Site Visit Requested');
                }
            }

            // Eager load User and Verification Documents
            $rows = $query->with(['user', 'verificationDocuments'])->get();

            foreach ($rows as $r) {
                // Determine Model Name/Label
                $modelName = class_basename($modelClass);
                $readableName = preg_replace('/(?<!\ )[A-Z]/', ' $0', $modelName);

                $registration_data->push([
                    'id' => $r->id,
                    'application_no' => $r->registration_id ?? 'N/A',
                    'application_name' => $readableName,
                    'user_name' => $r->user->name ?? 'N/A',
                    'user_email' => $r->user->email ?? 'N/A',
                    'submitted_date' => $r->submitted_at ? \Carbon\Carbon::parse($r->submitted_at)->format('d-m-Y') : 'N/A',
                    'status' => $r->workflow_status ?? $r->status,
                    'workflow_stage' => $r->current_stage,
                    'view_url' => route('admin.ApplicationForms.model.show', ['model' => strtolower($modelName), 'id' => $r->id]),
                    'model_slug' => strtolower($modelName), // For verification
                ]);
            }
        }

        // Sorting
        $registration_data = $registration_data->sortByDesc('submitted_date')->values();

        // Determine Page Title
        $pageTitle = match ($viewStatus) {
            'pending' => 'Pending Applications',
            'approved' => 'Approved Applications',
            'rejected' => 'Rejected Applications',
            'returned' => 'Returned / Clarification Applications',
            'clarification_list' => 'Clarification List',
            'site_visit_requested' => 'Site Visit Requests',
            'certificate_pending' => 'Certificate Pending',
            default => 'All Applications',
        };

        if (!$request->ajax()) {
            return view('admin.ApplicationForms.submissions_index', compact('registration_data', 'viewStatus', 'pageTitle'));
        }

        return \Yajra\DataTables\Facades\DataTables::of($registration_data)
            ->addIndexColumn()
            ->addColumn('status_badge', function ($row) {
                $status = $row['status'];
                $color = 'secondary';
                if ($status == 'Approved')
                    $color = 'success';
                if ($status == 'Rejected')
                    $color = 'danger';
                if ($status == 'Returned')
                    $color = 'warning';
                if ($status == 'Pending')
                    $color = 'warning';
                return '<span class="badge bg-' . $color . '">' . $status . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<a href="' . $row['view_url'] . '" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> View</a>';
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
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


        $usesHasDocuments = in_array(\App\Traits\HasDocuments::class, class_uses_recursive($application));

        if ($usesHasDocuments) {
            $existingDocsCount = $application->verificationDocuments()->count();

            // 1. Sync Logic (Additive) - Check for new/missing documents
            // A. Dynamic Documents (JSON fields etc.)
            if (method_exists($application, 'getDynamicDocuments')) {
                $dynamicDocs = $application->getDynamicDocuments();
                foreach ($dynamicDocs as $doc) {
                    $application->verificationDocuments()->firstOrCreate(
                        ['document_key' => $doc['key']],
                        [
                            'document_label' => $doc['label'],
                            'file_path' => $doc['file_path'],
                            'overall_status' => 'Pending',
                            'role_approvals' => []
                        ]
                    );
                }
            }
            // B. Standard Column Mapping
            elseif (method_exists($application, 'getDocumentMapping')) {
                $mapping = $application->getDocumentMapping();
                foreach ($mapping as $column => $label) {
                    if (!empty($application->$column)) {
                        $application->verificationDocuments()->firstOrCreate(
                            ['document_key' => $column],
                            [
                                'document_label' => $label,
                                'file_path' => $application->$column,
                                'overall_status' => 'Pending',
                                'role_approvals' => []
                            ]
                        );
                    }
                }
            }

            // C. Fallback: Legacy Column Sync (only if no new system docs exist at all, to avoid duplication)
            // Note: We might want to keep this for legacy apps, but for now, if the Model has traits, we rely on them.
            if ($application->verificationDocuments()->count() === 0) {
                // Do we still need to check legacy columns?
                // If the model DOES NOT have HasDocuments/getDynamicDocuments, we can't do anything here anyway with the new system.
                // So we assume the new system is the source of truth if the trait exists.
            }

            // 2. Sync from 'documents' table (Wizard Uploads) - Based on registration_id

            if (!empty($application->registration_id)) {
                $parentApp = \App\Models\frontend\ApplicationForm\Application::where('registration_id', $application->registration_id)->first();

                if ($parentApp && $parentApp->documents->count() > 0) {
                    $docLabels = [
                        'aadhar' => 'Aadhaar Card',
                        'pan' => 'PAN Card',
                        'business_pan' => 'Business PAN',
                        'udyam' => 'Udyam Registration',
                        'business_reg' => 'Business Registration',
                        'ownership' => 'Ownership Proof',
                        'property_photos' => 'Property Photo',
                        'character' => 'Character Certificate',
                        'society_noc' => 'Society NOC',
                        'building_perm' => 'Building Permission',
                        'gras_copy' => 'GRAS Challan',
                        'undertaking' => 'Undertaking',
                        'rental_agreement' => 'Rental Agreement',
                        'address_proof' => 'Address Proof',
                    ];

                    foreach ($parentApp->documents as $pDoc) {
                        $exists = $application->verificationDocuments()
                            ->where('file_path', $pDoc->path)
                            ->exists();

                        if (!$exists) {
                            $label = $docLabels[$pDoc->category] ?? ucfirst(str_replace('_', ' ', $pDoc->category));

                            $application->verificationDocuments()->create([
                                'document_key' => $pDoc->category,
                                'document_label' => $label,
                                'file_path' => $pDoc->path,
                                'overall_status' => 'Pending',
                                'role_approvals' => []
                            ]);
                        }
                    }
                }
            }

            $application->refresh();
        }

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

        if ($type === 'generic-application') {
            $application->load([
                'applicant',
                'property',
                'accommodation',
                'facilities',
                'photos',
                'documents'
            ]);

            $qrCode = 1;
            $logoPath = public_path('backend/mah-logo-300x277.png');
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

        // Check for specific view
        if (view()->exists("admin.ApplicationForms.reports.{$type}")) {

            $data = [
                'application' => $application, // Keep generic variable
                'registration' => $application, // Alias as per user request
                'type' => $type
            ];

            // Specific Data Loading for Provisional
            if ($type === 'provisional') {
                $data['regions'] = \Illuminate\Support\Facades\DB::table('divisions')->select('id', 'name')->get();
                $data['districts'] = \Illuminate\Support\Facades\DB::table('districts')->select('id', 'name')->get();
                $data['categories'] = \App\Models\Admin\Master\Category::all();
                $data['applicantTypes'] = \App\Models\Admin\Master\Enterprise::select('id', 'name')->get();
                $data['application_form'] = \App\Models\Admin\ApplicationForm::find($application->application_form_id);

                // Resolve derived variables that the view expects
                $data['enterprise'] = \App\Models\Admin\Master\Enterprise::find($application->enterprise_type);
                $data['division'] = \Illuminate\Support\Facades\DB::table('divisions')->where('id', $application->division_id)->first();
                $data['districtsData'] = \Illuminate\Support\Facades\DB::table('districts')->where('id', $application->district_id)->first();

                $cat = \App\Models\Admin\Master\Category::find($application->project_category); // Assuming project_category holds ID, or we need to check if it holds name
                // If project_category is a name, this lookup might fail. Let's check if it's numeric.
                if (is_numeric($application->project_category)) {
                    $data['Category_name'] = $cat ? $cat->name : $application->project_category;
                } else {
                    $data['Category_name'] = $application->project_category;
                }
            }

            return view("admin.ApplicationForms.reports.{$type}", $data);
        }

        // Debug/Fallback Explicit Check
        if ($type === 'provisional') {
            $data = [
                'application' => $application,
                'registration' => $application,
                'type' => $type
            ];
            // Load data again if the generic block above failed (it shouldn't if I duplicate logic, but better to just fix the logic)
            $data['regions'] = \Illuminate\Support\Facades\DB::table('divisions')->select('id', 'name')->get();
            $data['districts'] = \Illuminate\Support\Facades\DB::table('districts')->select('id', 'name')->get();
            $data['categories'] = \App\Models\Admin\Master\Category::all();
            $data['applicantTypes'] = \App\Models\Admin\Master\Enterprise::select('id', 'name')->get();
            $data['application_form'] = \App\Models\Admin\ApplicationForm::find($application->application_form_id);
            $data['enterprise'] = \App\Models\Admin\Master\Enterprise::find($application->enterprise_type);
            $data['division'] = \Illuminate\Support\Facades\DB::table('divisions')->where('id', $application->division_id)->first();
            $data['districtsData'] = \Illuminate\Support\Facades\DB::table('districts')->where('id', $application->district_id)->first();

            if (is_numeric($application->project_category)) {
                $cat = \App\Models\Admin\Master\Category::find($application->project_category);
                $data['Category_name'] = $cat ? $cat->name : $application->project_category;
            } else {
                $data['Category_name'] = $application->project_category;
            }

            return view('frontend.Application.provisional.reports', $data);
        }

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


}
