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
    const STATUS_CLARIFICATION = 'Clarification'; // To User
    const STATUS_RETURNED = 'Returned'; // Internal return (e.g. Dy -> Clerk)
    const STATUS_SITE_VISIT = 'Site Visit Report';
    const STATUS_SITE_VISIT_REQUESTED = 'Site Visit Requested';
    const STATUS_CERTIFICATE_PENDING = 'Certificate Pending'; // After Director Approval
    const STATUS_CERTIFICATE_ISSUED = 'Certificate Issued';

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

        // Default to short chain or whatever logic for others
        // User requested this specific flow for the 3 main models, but let's apply the structure generally if needed.
        // For now, assuming the other models use the standard short chain.
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
                // Final Approval - Director
                $application->workflow_status = self::STATUS_CERTIFICATE_PENDING; // Moves to Asst Director for upload
                $application->status = 'approved';

                // Note: The certificate is uploaded by Asst Director later.
            }

            $application->save();
            return $nextStage;
        });
    }

    public function returnBack($application, $user, $remark)
    {
        return DB::transaction(function () use ($application, $user, $remark) {
            $currentStage = $application->current_stage;

            // Custom Return Logic based on User Request:
            // Joint Director -> Asst Director (Tips: "ye bhejega bhir clerk ke pass" implied recursive return, but let's stick to immediate target)
            // Dy Director -> Asst Director
            // Asst Director -> Clerk

            $targetStage = null;

            if ($currentStage === self::ROLE_JOINT_DIRECTOR) {
                $targetStage = self::ROLE_ASST_DIRECTOR;
            } elseif ($currentStage === self::ROLE_DY_DIRECTOR) {
                $targetStage = self::ROLE_ASST_DIRECTOR;
            } elseif ($currentStage === self::ROLE_ASST_DIRECTOR) {
                $targetStage = self::ROLE_CLERK;
            } else {
                // Default fallback (e.g. Director -> Joint Director)
                $targetStage = $this->getPreviousStage($application);
            }

            // Log
            ApplicationWorkflowLog::create([
                'application_type' => get_class($application),
                'application_id' => $application->id,
                'stage' => $currentStage,
                'status' => self::STATUS_RETURNED,
                'user_id' => $user->id,
                'remark' => $remark,
                'is_public' => false
            ]);

            if ($targetStage) {
                // RESET DOCUMENT STATUS for the Target Stage (so they can review again)
                if (method_exists($application, 'verificationDocuments')) {
                    foreach ($application->verificationDocuments as $doc) {
                        $approvals = $doc->role_approvals ?? [];

                        // Current stage marked as Pending (since they returned it)
                        if (isset($approvals[$currentStage])) {
                            $approvals[$currentStage]['status'] = 'Pending';
                        }

                        // Target stage must also be Pending to allow them to take action
                        if (isset($approvals[$targetStage])) {
                            $approvals[$targetStage]['status'] = 'Pending';
                        }

                        $doc->role_approvals = $approvals;
                        $doc->save();
                    }
                }

                $application->current_stage = $targetStage;
                $application->workflow_status = self::STATUS_RETURNED;
                $application->save();
            }

            return $targetStage;
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

            // Add to Verification Documents to enforce approval
            if (method_exists($application, 'verificationDocuments')) {
                $application->verificationDocuments()->updateOrCreate(
                    ['document_key' => 'site_visit_report'],
                    [
                        'document_label' => 'Site Visit Report',
                        'file_path' => $file_path,
                        'overall_status' => 'Pending',
                        'role_approvals' => [] // Reset so Dy Director must approve new report
                    ]
                );

                if ($taluka_file_path) {
                    $application->verificationDocuments()->updateOrCreate(
                        ['document_key' => 'taluka_report_file'],
                        [
                            'document_label' => 'Taluka Agri Officer Inspection Report',
                            'file_path' => $taluka_file_path,
                            'overall_status' => 'Pending',
                            'role_approvals' => []
                        ]
                    );
                }
            }

            $application->workflow_status = self::STATUS_PENDING; // Moves back to Dy Director's view
            $application->save();

            return $log;
        });
    }

    public function rejectFullForm($application, $user, $remark)
    {
        return DB::transaction(function () use ($application, $user, $remark) {
            $currentStage = $application->current_stage;

            // Log
            ApplicationWorkflowLog::create([
                'application_type' => get_class($application),
                'application_id' => $application->id,
                'stage' => $currentStage,
                'status' => self::STATUS_REJECTED,
                'user_id' => $user->id,
                'remark' => $remark,
                'is_public' => true
            ]);

            $application->workflow_status = self::STATUS_REJECTED;
            $application->status = 'rejected';
            $application->save();

            return true;
        });
    }

    public function requestSiteVisit($application, $user)
    {
        return DB::transaction(function () use ($application, $user) {
            $currentStage = $application->current_stage; // Should be Dy Director

            // Log
            ApplicationWorkflowLog::create([
                'application_type' => get_class($application),
                'application_id' => $application->id,
                'stage' => $currentStage,
                'status' => self::STATUS_SITE_VISIT_REQUESTED,
                'user_id' => $user->id,
                'remark' => 'Site Visit Report Requested',
                'is_public' => false
            ]);

            // Status update
            // We keep the current stage (Dy Director) but change status so Clerk sees it in "Site Visit Upload" list
            // Or we check specific flag. Let's use workflow_status.
            $application->workflow_status = self::STATUS_SITE_VISIT_REQUESTED;
            $application->save();

            return true;
        });
    }

    public function uploadCertificate($application, $user, $filePath)
    {
        return DB::transaction(function () use ($application, $user, $filePath) {

            // Log
            ApplicationWorkflowLog::create([
                'application_type' => get_class($application),
                'application_id' => $application->id,
                'stage' => self::ROLE_ASST_DIRECTOR,
                'status' => self::STATUS_CERTIFICATE_ISSUED,
                'user_id' => $user->id,
                'remark' => 'Certificate Uploaded',
                'is_public' => true
            ]);

            $application->workflow_status = self::STATUS_CERTIFICATE_ISSUED;
            $application->certificate_path = $filePath; // Ensure model has this field or add it
            $application->save();

            // Sync to Parent Application if exists
            if (method_exists($application, 'application') && $application->application) {
                $item = $application->application;
                $item->workflow_status = self::STATUS_CERTIFICATE_ISSUED;
                $item->status = 'approved';
                $item->save();
            } elseif (!empty($application->application_id)) {
                $parent = \App\Models\frontend\ApplicationForm\Application::find($application->application_id);
                if ($parent) {
                    $parent->workflow_status = self::STATUS_CERTIFICATE_ISSUED;
                    $parent->status = 'approved';
                    $parent->save();
                }
            }

            return true;
        });
    }
}
