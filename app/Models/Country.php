<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Country extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','iso_code','is_active'];

    public function states() { return $this->hasMany(State::class); }
}
