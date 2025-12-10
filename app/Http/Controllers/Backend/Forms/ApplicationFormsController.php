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
class ApplicationFormsController extends Controller
{

    public function index()
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
                $registration_data->push((object)[
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

    public function show($model, $id)
    {
        switch ($model) {
            case 'adventure':
                $data = AdventureApplication::findOrFail($id);
                return view('frontend.applications.adventure.report', compact('data'));

            case 'agriculture':
                $data = AgricultureRegistration::findOrFail($id);
                return view('frontend.applications.agriculture.report', compact('data'));

            case 'application':
            default:
                $data = Application::findOrFail($id);
                return view('frontend.applications.default.report', compact('data'));
        }
    }

    public function data()
    {
        $apps = Application::where('user_id', auth()->id())->latest()->get();

        $models = [
            // TourismApartment::class,
            AdventureApplication::class,
            AgricultureRegistration::class,

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
        return view('frontend.wizard.index', compact('apps','registration_data', 'forms'));
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
