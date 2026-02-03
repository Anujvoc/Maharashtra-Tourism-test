<?php

namespace App\Http\Controllers\frontend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\frontend\ApplicationForm\Application;
use App\Models\frontend\Api\ApplicationMovement;
use App\Models\Admin\ApplicationForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\frontend\ApplicationForm\TourismApartment;
use App\Models\frontend\ApplicationForm\AdventureApplication;
use App\Models\frontend\ApplicationForm\AgricultureRegistration;
use App\Models\frontend\ApplicationForm\WomenCenteredTourismRegistration;
use App\Models\frontend\ApplicationForm\IndustrialRegistration;
use App\Models\frontend\CaravanRegistration\CaravanRegistration;

use App\Models\frontend\ApplicationForm\StampDutyApplication;
use App\Models\frontend\ApplicationForm\ProvisionalRegistration;
use App\Models\frontend\ApplicationForm\EligibilityRegistration;
use App\Models\frontend\ApplicationForm\TouristVillaRegistration;


class TrackController extends Controller
{
    public function trackStatus(Request $request)
    {
        // 1️⃣ Input Validation (Decrypt if they send encrypted body)
        // Note: Currently assuming they send raw JSON as per your request snippet.
        // If they send encrypted string, you must decrypt $request->getContent() first.

        $appId = $request->AppID;
        $language = $request->Language ?? 'EN'; // Handle Language

        if (!$appId) {
            return response()->json(["error" => "Application ID is required"], 400);
        }

        // 2️⃣ Find Application
        $app = Application::where('registration_id', $appId)->first();

        if (!$app) {
            // Return simplified error or standard structure with error flag if required
            return response()->json(["error" => "Application ID not found"], 404);
        }

        // 3️⃣ Basic Details
        $application_form = ApplicationForm::where('id', $app->application_form_id)->first();
        $ServiceName = $application_form->name ?? 'Tourism Service';

        $user = User::where('id', $app->user_id)->first();
        $applicant_name = $user->name ?? '';

        // 4️⃣ Movements (Process History)
        $movements = ApplicationMovement::where('application_id', $app->registration_id)
            ->orderBy('desk_number', 'asc')
            ->get();

        $deskDetails = [];

        foreach ($movements as $m) {
            // Note: If Language is MR, you might want to fetch marathi column or translate
            $deskDetails[] = [
                "DeskNumber" => "Desk " . $m->desk_number,
                "ReviewActionBy" => $m->officer_name ?? "",
                "ReviewActionDateTime" => date('d-M-Y,H:i:s', strtotime($m->action_datetime)),
                "ReviewActionDetails" => $m->action ?? ""
            ];
        }

        // 5️⃣ Final Decision Logic
        // approved = 0, rejected = 1, pending_office = 2, pending_citizen = 3
        $finalDecision = 2; // Default: Pending at Department
        $status = strtolower($app->form_current_status);

        if ($status == 'approved') {
            $finalDecision = 0;
        } elseif ($status == 'rejected') {
            $finalDecision = 1;
        } elseif ($status == 'pending_citizen') {
            $finalDecision = 3;
        } elseif ($status == 'pending') {
            $finalDecision = 2;
        }

        // 6️⃣ Construct Response
        $responseData = [
            "ApplicationID" => $app->registration_id ?? '',
            "ServiceName" => $ServiceName,
            "ApplicantName" => $applicant_name,
            "EstimatedDisbursalDays" => 7,
            "ApplicationSubmissionDate" => $app->created_at ? $app->created_at->format('d-M-Y,H:i:s') : "",
            "ApplicationPaymentDate" => $app->payment_date ? date('d-M-Y,H:i:s', strtotime($app->payment_date)) : "", // Ensure column exists or leave empty
            "NextActionRequiredDetails" => "",
            "FinalDecision" => $finalDecision,
            "TotalNumberOfDesks" => 5,
            "CurrentDeskNumber" => $app->current_desk_number ?? 0,
            "NextDeskNumber" => ($finalDecision == 0 || $finalDecision == 1) ? 0 : ($app->current_desk_number + 1),
            "DeskDetails" => $deskDetails
        ];

        // 7️⃣ Return Response
        // Usually Govt portals expect Encrypted String. 
        // If testing locally or they allow raw JSON, use json response.
        // For Production/Strict mode, uncomment encryption.

        // $encrypted = $this->encryptData(json_encode($responseData));
        // return response($encrypted);

        return response()->json($responseData);
    }

    // 🔐 Encryption function (AES-256-CBC)
    private function encryptData($plainText)
    {
        $key = config('services.aaplesarkar.encrypt_key');
        $iv = config('services.aaplesarkar.encrypt_iv');

        $encrypted = openssl_encrypt(
            $plainText,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        // Hex / base64 format (usually base64 use hota hai)
        return base64_encode($encrypted);
    }
    
    // API for Aaple Sarkar RTS Dashboard Integration and district-wise and  department-wise application statistics

    public function index()
    {
        $models = [
            Application::class,
            AdventureApplication::class,
            AgricultureRegistration::class,
            WomenCenteredTourismRegistration::class,
            IndustrialRegistration::class,
            CaravanRegistration::class,
            StampDutyApplication::class,
            ProvisionalRegistration::class,
            EligibilityRegistration::class,
        ];

        $districtStats = [];

        $departmentStatus = [
            'submitted' => 0,
            'approved' => 0,
            'rejected' => 0,
            'pending' => 0,
            'certificate_completed' => 0,
            'total_applications_disposed' => 0,
        ];

        $totalApplications = 0;

        foreach ($models as $modelClass) {

            $table = (new $modelClass)->getTable();

            /* =========================
               SUBMITTED (is_apply = 1)
            ========================= */
            $submitted = DB::table($table)
                ->join('districts', 'districts.id', '=', "$table.district_id")
                ->select(
                    'districts.name as district_name',
                    DB::raw('COUNT(*) as total')
                )
                ->where("$table.is_apply", 1)
                ->groupBy('districts.name')
                ->get();

            foreach ($submitted as $row) {
                $this->initDistrict($districtStats, $row->district_name);

                $districtStats[$row->district_name]['submitted'] += $row->total;
                $districtStats[$row->district_name]['total_applications'] += $row->total;

                $departmentStatus['submitted'] += $row->total;
                $totalApplications += $row->total;
            }

            /* =========================
               APPROVED / REJECTED / PENDING
            ========================= */
            $statusData = DB::table($table)
                ->join('districts', 'districts.id', '=', "$table.district_id")
                ->select(
                    'districts.name as district_name',
                    "$table.status",
                    DB::raw('COUNT(*) as total')
                )
                ->whereIn("$table.status", ['approved', 'rejected', 'pending'])
                ->groupBy('districts.name', "$table.status")
                ->get();

            foreach ($statusData as $row) {
                $this->initDistrict($districtStats, $row->district_name);

                $districtStats[$row->district_name][$row->status] += $row->total;
                $districtStats[$row->district_name]['total_applications'] += $row->total;

                $departmentStatus[$row->status] += $row->total;
                $totalApplications += $row->total;
            }

            /* =========================
               CERTIFICATE COMPLETED
            ========================= */
            $certificateData = DB::table($table)
                ->join('districts', 'districts.id', '=', "$table.district_id")
                ->select(
                    'districts.name as district_name',
                    DB::raw('COUNT(*) as total')
                )
                ->where("$table.workflow_status", 'Certificate Generated')
                ->groupBy('districts.name')
                ->get();

            foreach ($certificateData as $row) {
                $this->initDistrict($districtStats, $row->district_name);

                $districtStats[$row->district_name]['certificate_completed'] += $row->total;
                $districtStats[$row->district_name]['total_applications'] += $row->total;

                $departmentStatus['certificate_completed'] += $row->total;
                $totalApplications += $row->total;
            }
        }

        /* =========================
           DISPOSED CALCULATION
        ========================= */
        foreach ($districtStats as &$district) {
            $district['total_applications_disposed'] =
                $district['approved'] +
                $district['rejected'] +
                $district['certificate_completed'];
        }

        $departmentStatus['total_applications_disposed'] =
            $departmentStatus['approved'] +
            $departmentStatus['rejected'] +
            $departmentStatus['certificate_completed'];

        return response()->json([
            'department_wise' => [
                'department_name' => 'Tourism',
                'total_applications_received' => $totalApplications,
                'status_summary' => $departmentStatus,
            ],
            'district_wise_statistics' => array_values($districtStats),
        ]);
    }

    /* =========================
       Helper
    ========================= */
    private function initDistrict(&$districtStats, $districtName)
    {
        if (!isset($districtStats[$districtName])) {
            $districtStats[$districtName] = [
                'district_name' => $districtName,
                'total_applications' => 0,
                'submitted' => 0,
                'approved' => 0,
                'rejected' => 0,
                'pending' => 0,
                'certificate_completed' => 0,
                'total_applications_disposed' => 0,
            ];
        }
    }
    
    
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
