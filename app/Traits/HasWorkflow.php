<?php

namespace App\Traits;

use App\Models\User; // Adjust if User is elsewhere? No, App\Models\User is fine.
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasWorkflow
{
    /**
     * Get the workflow logs for the application.
     */
    public function workflowLogs(): MorphMany
    {
        // We assume the log model is in App\Models\ApplicationWorkflowLog
        // I need to create that model actually, or use anonymous class if not needed, but better to have a model.
        return $this->morphMany(\App\Models\ApplicationWorkflowLog::class, 'application');
    }

    /**
     * Check if the application is at a specific stage.
     */
    public function isAtStage(string $stage): bool
    {
        return $this->current_stage === $stage;
    }

    public function scopeAtStage($query, string $stage)
    {
        return $query->where('current_stage', $stage);
    }

    /**
     * Scope a query to only include applications that are pending.
     */
    public function scopePending($query)
    {
        // Assuming 'Pending' status means active workflow
        // But user requirement says 'Pending' is not a status log, but current stage.
        // We might check workflow_status != 'Approved'
        return $query->whereNotIn('workflow_status', ['Approved', 'Rejected']);
    }
}
