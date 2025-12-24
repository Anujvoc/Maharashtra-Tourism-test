<?php

namespace App\Services;

use App\Models\ApplicationWorkflowLog;
use Illuminate\Support\Facades\DB;
use App\Models\frontend\ApplicationForm\EligibilityRegistration;
use App\Models\frontend\ApplicationForm\ProvisionalRegistration;
use App\Models\frontend\ApplicationForm\StampDutyApplication;

class WorkflowService
{
    // Define Roles
    const ROLE_CLERK = 'Clerk';
    const ROLE_ASST_DIRECTOR = 'Asst Director';
    const ROLE_DY_DIRECTOR = 'Dy Director';
    const ROLE_JOINT_DIRECTOR = 'Joint Director';
    const ROLE_DIRECTOR = 'Director';

    // Statuses
    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';
    const STATUS_RETURNED = 'Returned'; // Internal return (e.g. Dy -> Clerk)
    const STATUS_CLARIFICATION = 'Clarification'; // To User
    const STATUS_SITE_VISIT = 'Site Visit Report';

    public function getWorkflowStages($application)
    {
        // Models that include Asst Director
        $longChainModels = [
            EligibilityRegistration::class,
            ProvisionalRegistration::class,
            StampDutyApplication::class,
        ];

        if (in_array(get_class($application), $longChainModels)) {
            return [
                self::ROLE_CLERK,
                self::ROLE_ASST_DIRECTOR,
                self::ROLE_DY_DIRECTOR,
                self::ROLE_JOINT_DIRECTOR,
                self::ROLE_DIRECTOR,
            ];
        }

        // Default to short chain for others (Villa, Agro, Caravan, and Generic Application)
        // Note: User said "Asst Director ka role nahi hai" for this model, so short chain (Clerk -> Dy -> Joint -> Director) is correct.
        return [
            self::ROLE_CLERK,
            self::ROLE_DY_DIRECTOR,
            self::ROLE_JOINT_DIRECTOR,
            self::ROLE_DIRECTOR,
        ];
    }

    public function getNextStage($application)
    {
        $stages = $this->getWorkflowStages($application);
        $current = $application->current_stage;

        $index = array_search($current, $stages);
        if ($index === false || $index === count($stages) - 1) {
            return null; // No next stage (Finished)
        }

        return $stages[$index + 1];
    }

    public function getPreviousStage($application)
    {
        $stages = $this->getWorkflowStages($application);
        $current = $application->current_stage;

        $index = array_search($current, $stages);
        if ($index === false || $index === 0) {
            return null;
        }

        return $stages[$index - 1];
    }

    public function forward($application, $user, $remark = null)
    {
        return DB::transaction(function () use ($application, $user, $remark) {
            $currentStage = $application->current_stage;
            $nextStage = $this->getNextStage($application);

            // 🔍 DOCUMENT VERIFICATION CHECK
            // Ensure all documents for this application are approved by the CURRENT role.
            if (method_exists($application, 'verificationDocuments')) {
                $role = $user->getRoleNames()->first();
                $pendingDocs = $application->verificationDocuments->filter(function ($doc) use ($role) {
                    // If doc status for this role is NOT Approved, it's pending/rejected
                    // verificationDocuments stores approval in JSON role_approvals
                    $approvals = $doc->role_approvals ?? [];
                    $status = $approvals[$role]['status'] ?? 'Pending';
                    return $status !== 'Approved';
                });

                if ($pendingDocs->isNotEmpty()) {
                    throw new \Exception("Cannot forward: " . $pendingDocs->count() . " documents are not approved by " . $role);
                }
            }

            // Log the approval
            ApplicationWorkflowLog::create([
                'application_type' => get_class($application),
                'application_id' => $application->id,
                'stage' => $currentStage,
                'status' => self::STATUS_APPROVED,
                'user_id' => $user->id,
                'remark' => $remark,
                'is_public' => false
            ]);

            // Update application
            if ($nextStage) {
                $application->current_stage = $nextStage;
                $application->workflow_status = self::STATUS_PENDING; // Pending for next role
            } else {
                // Final Approval
                $application->workflow_status = 'Certificate Generated';
                $application->status = 'approved'; // Update main status to Approved

                // Sync to Parent Application if exists
                if (method_exists($application, 'application') && $application->application) {
                    $item = $application->application;
                    $item->workflow_status = 'Certificate Generated';
                    $item->status = 'approved';
                    $item->save();
                }
                // Fallback: check for application_id column directly
                elseif (!empty($application->application_id)) {
                    $parent = \App\Models\frontend\ApplicationForm\Application::find($application->application_id);
                    if ($parent) {
                        $parent->workflow_status = 'Certificate Generated';
                        $parent->status = 'approved';
                        $parent->save();
                    }
                }
            }

            $application->save();
            return $nextStage;
        });
    }

    public function returnBack($application, $user, $remark)
    {
        return DB::transaction(function () use ($application, $user, $remark) {
            $currentStage = $application->current_stage;

            // Logic: Return usually goes back to Clerk or User?
            // User says: "return back to clerk with clarification" or "return back to user".
            // If "Return to Clerk/Asst Director" from Dy Director.

            // For now, let's implement return to immediate previous internal stage
            $prevStage = $this->getPreviousStage($application);

            // Log
            ApplicationWorkflowLog::create([
                'application_type' => get_class($application),
                'application_id' => $application->id,
                'stage' => $currentStage,
                'status' => self::STATUS_RETURNED,
                'user_id' => $user->id,
                'remark' => $remark,
                'is_public' => false // Internal remarks
            ]);

            if ($prevStage) {
                // RESET DOCUMENT STATUS FOR PREVIOUS STAGE
                // If Asst Director returns to Clerk, Clerk's 'Approved' docs become 'Pending'
                if (method_exists($application, 'verificationDocuments')) {
                    foreach ($application->verificationDocuments as $doc) {
                        $approvals = $doc->role_approvals ?? [];

                        // UNCONDITIONAL RESET (User Request: "sara reset ho jay")
                        // Reset Previous Stage (Clerk) - Force re-verification
                        if (isset($approvals[$prevStage])) {
                            $approvals[$prevStage]['status'] = 'Pending';
                        }

                        // Reset Current Stage (Asst Director) - Show Pending in history
                        $approvals[$currentStage]['status'] = 'Pending';

                        $doc->role_approvals = $approvals;
                        $doc->save();
                    }
                }

                $application->current_stage = $prevStage;
                $application->workflow_status = self::STATUS_RETURNED;
                $application->save();
            }

            return $prevStage;
        });
    }

    public function sendToUser($application, $user, $remark)
    {
        return DB::transaction(function () use ($application, $user, $remark) {
            $currentStage = $application->current_stage;

            // Log
            ApplicationWorkflowLog::create([
                'application_type' => get_class($application),
                'application_id' => $application->id,
                'stage' => $currentStage,
                'status' => self::STATUS_CLARIFICATION,
                'user_id' => $user->id,
                'remark' => $remark,
                'is_public' => true // VISIBLE TO USER
            ]);

            // $application->current_stage = self::ROLE_CLERK; // REMOVED: Keep at current stage
            $application->workflow_status = self::STATUS_CLARIFICATION;
            $application->save();

            return true;
        });
    }

    public function siteVisitReport($application, $user, $remark, $file_path, $taluka_file_path = null)
    {
        return DB::transaction(function () use ($application, $user, $remark, $file_path, $taluka_file_path) {
            $currentStage = $application->current_stage;

            // Log
            $log = ApplicationWorkflowLog::create([
                'application_type' => get_class($application),
                'application_id' => $application->id,
                'stage' => $currentStage,
                'status' => self::STATUS_SITE_VISIT,
                'user_id' => $user->id,
                'remark' => $remark,
                'is_public' => false
            ]);

            // Create Site Visit Report Entry
            \App\Models\SiteVisitReport::create([
                'application_type' => get_class($application),
                'application_id' => $application->id,
                'workflow_log_id' => $log->id,
                'user_id' => $user->id,
                'file_path' => $file_path,
                'taluka_file_path' => $taluka_file_path
            ]);

            $application->touch(); // Update updated_at

            return $log;
        });
    }
}
