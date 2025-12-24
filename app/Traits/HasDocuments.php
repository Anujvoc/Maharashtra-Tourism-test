<?php

namespace App\Traits;

use App\Models\ApplicationDocument;

trait HasDocuments
{
    public function verificationDocuments()
    {
        return $this->morphMany(ApplicationDocument::class, 'application');
    }

    /**
     * Override this in the model to provide mapping of column_name => Label
     */
    public function getDocumentMapping()
    {
        return [];
    }
}
