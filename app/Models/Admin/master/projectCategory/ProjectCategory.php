<?php

namespace App\Models\Admin\master\projectCategory;

use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
    protected $table = 'project_categories';

    protected $fillable = ['name' ,'units', 'is_active'];

    public function unitNames()
{
    // Get the JSON field
    $ids = is_string($this->units)
        ? json_decode($this->units, true)
        : $this->units;

    // When null, convert to empty array
    if (!is_array($ids)) {
        $ids = [];
    }

    // Return as a COLLECTION (fix for isEmpty)
    return ProjectType::whereIn('id', $ids)->pluck('name');
}
}
