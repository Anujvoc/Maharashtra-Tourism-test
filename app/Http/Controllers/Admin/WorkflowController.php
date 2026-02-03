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


        $this->workflowService->forward($application, Auth::user(), $request->input('remark'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Application approved and forwarded successfully.',
                'new_stage' => $application->fresh()->current_stage, // Optional: return new stage for UI update
                'new_status' => $application->fresh()->workflow_status
            ]);
        }

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

        // Cleanup Logic Removed as per requirement: Document should not be deleted on return.
        // if (Auth::user()->hasRole('Dy Director')) { ... }

        $this->workflowService->returnBack($application, Auth::user(), $remark);

        return redirect()->back()->with('success', 'Application returned successfully.');
    }

    public function sendClarification(Request $request, $type, $id)
    {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass)
            abort(404);

        $application = $modelClass::findOrFail($id);

        if (Auth::user()->hasRole('Clerk')) {
            // User requested to ALLOW Clerk to send clarification.
            // Removing the restriction.
        }

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
        $user = Auth::user();

        // Cleanup old reports
        $existingReport = \App\Models\SiteVisitReport::where('application_id', $application->id)
            ->where('application_type', get_class($application))
            ->first();

        if ($existingReport) {
            // Delete File
            if ($existingReport->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($existingReport->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($existingReport->file_path);
            }
            // Delete Taluka File
            if ($existingReport->taluka_file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($existingReport->taluka_file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($existingReport->taluka_file_path);
            }

            // Delete Log
            if ($existingReport->workflowLog) {
                $existingReport->workflowLog->delete();
            }

            // Delete Report Record
            $existingReport->delete();
        }

        $rules = [
            'site_visit_report' => 'required|file|mimes:pdf|max:10240',
            'remark' => 'required' // Keeping remark required as per original logic
        ];

        // Agriculture requires Taluka Report
        if ($modelClass === AgricultureRegistration::class) {
            $rules['taluka_report_file'] = 'required|file|mimes:pdf|max:10240';
        }

        $request->validate($rules);

        $file_path = $request->file('site_visit_report')->store('site-reports', 'public');

        $taluka_file_path = null;
        if ($request->hasFile('taluka_report_file')) {
            $taluka_file_path = $request->file('taluka_report_file')->store('site-reports', 'public');
        }

        $this->workflowService->siteVisitReport($application, $user, $request->input('remark'), $file_path, $taluka_file_path);

        return redirect()->back()->with('success', 'Site visit report submitted successfully.');
    }
    public function reject(Request $request, $type, $id)
    {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass)
            abort(404);

        $application = $modelClass::findOrFail($id);
        $remark = $request->input('remark');

        if (!$remark) {
            return redirect()->back()->with('error', 'Remark is required for rejection.');
        }

        $this->workflowService->rejectFullForm($application, Auth::user(), $remark);

        return redirect()->back()->with('success', 'Application rejected successfully.');
    }

    public function requestSiteVisitAction(Request $request, $type, $id)
    {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass)
            abort(404);

        $application = $modelClass::findOrFail($id);

        $this->workflowService->requestSiteVisit($application, Auth::user());

        return redirect()->back()->with('success', 'Site Visit Requested. Notification sent to Clerk.');
    }

    public function uploadCertificate(Request $request, $type, $id)
    {
        $modelClass = $this->getModelClass($type);
        if (!$modelClass)
            abort(404);

        $application = $modelClass::findOrFail($id);

        $request->validate([
            'certificate_file' => 'required|file|mimes:pdf|max:5120',
        ]);

        $filePath = $request->file('certificate_file')->store('certificates', 'public');

        $this->workflowService->uploadCertificate($application, Auth::user(), $filePath);

        return redirect()->back()->with('success', 'Certificate uploaded and issued successfully.');
    }
}
