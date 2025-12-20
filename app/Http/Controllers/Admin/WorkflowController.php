<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WorkflowService;
use App\Models\frontend\ApplicationForm\TouristVillaRegistration;
use App\Models\frontend\ApplicationForm\AdventureApplication;
use App\Models\frontend\ApplicationForm\AgricultureRegistration;
use App\Models\frontend\ApplicationForm\WomenCenteredTourismRegistration;
use App\Models\frontend\ApplicationForm\IndustrialRegistration;
use App\Models\frontend\ApplicationForm\ProvisionalRegistration;
use App\Models\frontend\ApplicationForm\EligibilityRegistration;
use App\Models\frontend\ApplicationForm\StampDutyApplication;
use App\Models\frontend\ApplicationForm\TourismApartment;
use App\Models\frontend\CaravanRegistration\CaravanRegistration;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{
    protected $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Map slug or type string to Model Class
     */
    protected function getModelClass($type)
    {
        $map = [
            'tourist-villa' => TouristVillaRegistration::class,
            'adventure' => AdventureApplication::class,
            'agriculture' => AgricultureRegistration::class,
            'women-centered' => WomenCenteredTourismRegistration::class,
            'industrial' => IndustrialRegistration::class,
            'provisional' => ProvisionalRegistration::class,
            'eligibility' => EligibilityRegistration::class,
            'stamp-duty' => StampDutyApplication::class,
            'tourism-apartment' => TourismApartment::class,
            'caravan' => CaravanRegistration::class,
            'generic-application' => \App\Models\frontend\ApplicationForm\Application::class,
        ];

        return $map[$type] ?? null;
    }

    public function approve(Request $request, $type, $id)
    {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass)
            abort(404);

        $application = $modelClass::findOrFail($id);

        // Authorization check could be here (e.g. is user allowed to approve at this stage?)
        // Assuming middleware handles role access to this controller generally.
        // But we should verify if the user's role matches the application's current stage.
        // Simple check:
        // if (!auth()->user()->hasRole($application->current_stage)) { return back()->with('error', 'Unauthorized'); }

        $this->workflowService->forward($application, Auth::user(), $request->input('remark'));

        return redirect()->back()->with('success', 'Application approved successfully.');
    }

    public function returnBack(Request $request, $type, $id)
    {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass)
            abort(404);

        $application = $modelClass::findOrFail($id);

        $remark = $request->input('remark');
        if (!$remark) {
            return redirect()->back()->with('error', 'Remark is required for returning.');
        }

        $this->workflowService->returnBack($application, Auth::user(), $remark);

        return redirect()->back()->with('success', 'Application returned successfully.');
    }

    public function sendClarification(Request $request, $type, $id)
    {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass)
            abort(404);

        $application = $modelClass::findOrFail($id);

        $remark = $request->input('remark');
        if (!$remark) {
            return redirect()->back()->with('error', 'Remark is required for clarification.');
        }

        $this->workflowService->sendToUser($application, Auth::user(), $remark);

        return redirect()->back()->with('success', 'Clarification sent to user.');
    }

    public function submitSiteReport(Request $request, $type, $id)
    {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass)
            abort(404);

        $application = $modelClass::findOrFail($id);

        $request->validate([
            'report_file' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'remark' => 'required'
        ]);

        $path = $request->file('report_file')->store('site-reports');

        $this->workflowService->siteVisitReport($application, Auth::user(), $request->input('remark'), $path);

        return redirect()->back()->with('success', 'Site visit report submitted.');
    }
}
