<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisitReport extends Model
{
    protected $guarded = [];

    public function application()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workflowLog()
    {
        return $this->belongsTo(ApplicationWorkflowLog::class, 'workflow_log_id');
    }
}
