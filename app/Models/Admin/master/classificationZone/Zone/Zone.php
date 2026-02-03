<?php

namespace App\Models\Admin\master\classificationZone\Zone;

use App\Models\Admin\master\classificationZone\Area\Area;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $table = 'zones';

    protected $fillable = ['name' ,'areas', 'is_active'];

    public function areaNames()
{
    // Get the JSON field
    $ids = is_string($this->areas)
        ? json_decode($this->areas, true)
        : $this->areas;

    // When null, convert to empty array
    if (!is_array($ids)) {
        $ids = [];
    }

    // Return as a COLLECTION (fix for isEmpty)
    return Area::whereIn('id', $ids)->pluck('name');
}

}
