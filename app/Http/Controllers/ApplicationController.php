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

class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        $app = Application::create([
            'user_id'   => auth()->id(),
            'slug_id'   => (string) Str::ulid(),
            'status'    => 'draft',
            'is_apply'  => false,
            'current_step' => 1,
        ]);

        return redirect()->route('wizard.show', [$app, 'step' => 1]);
    }

    public function index()
    {
        $userId = auth()->id();

        // User ke Application records (header info, but optional)
        $apps = Application::where('user_id', $userId)
            ->where('application_form_id', '!=', 1)
            ->latest()
            ->get();


        $models = [
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
                        'current_step'
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
                        'submitted_at'
                    )
                    ->get();
            }

            $rows->each(function ($r) use (&$registration_data, $modelClass) {
                $registration_data->push((object) [
                    'id'                 => $r->id,                        // registration model id
                    'application_id'     => $r->application_id ?? null,    // sirf provisional me hoga
                    'application_form_id' => $r->application_form_id,
                    'registration_id'    => $r->registration_id,
                    'status'             => $r->status,
                    'submitted_at'       => $r->submitted_at ? Carbon::parse($r->submitted_at) : null,
                    'current_step'       => $r->current_step ?? null,      // sirf provisional me hoga
                    'model'              => $modelClass,
                ]);
            });
        }

        // latest submitted first
        $registration_data = $registration_data
            ->sortByDesc(function ($item) {
                return $item->submitted_at ? $item->submitted_at->timestamp : 0;
            })
            ->values();

        // Form names ke liye
        $formIds = $apps->pluck('application_form_id')->unique()->filter()->values()->all();
        $forms   = ApplicationForm::whereIn('id', $formIds)->get()->keyBy('id');

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
                ->select('id', 'application_form_id', 'registration_id', 'status', 'submitted_at')
                ->get();


            // $rows = $modelClass::where('user_id', auth()->id())
            // ->select('id', 'application_form_id', 'registration_id', 'status', 'submitted_at')
            // ->get();

            $rows->each(function ($r) use (&$registration_data, $modelClass) {
                $registration_data->push((object)[
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
}
