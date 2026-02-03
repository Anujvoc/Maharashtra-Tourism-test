<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationWorkflowLog extends Model
{
    protected $guarded = [];

    public function application(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function siteVisitReport()
    {
        return $this->hasOne(SiteVisitReport::class, 'workflow_log_id');
    }
}
