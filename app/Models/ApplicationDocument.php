<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationDocument extends Model
{
    protected $fillable = [
        'application_type',
        'application_id',
        'document_key',
        'document_label',
        'file_path',
        'role_approvals',
        'overall_status'
    ];

    protected $casts = [
        'role_approvals' => 'array',
    ];

    public function application()
    {
        return $this->morphTo();
    }
}
