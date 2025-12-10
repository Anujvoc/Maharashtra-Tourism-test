<?php

namespace App\Models\Admin\master;

use Illuminate\Database\Eloquent\Model;
use App\Models\District;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Divisions extends Model
{
       use HasFactory;

    protected $fillable = ['name', 'code', 'districts','is_active'];

    protected $casts = [
    'districts' => 'array',
];


public function districtNames()
{
    // Get the JSON field
    $ids = is_string($this->districts)
        ? json_decode($this->districts, true)
        : $this->districts;

    // When null, convert to empty array
    if (!is_array($ids)) {
        $ids = [];
    }

    // Return as a COLLECTION (fix for isEmpty)
    return District::whereIn('id', $ids)->pluck('name');
}




}
