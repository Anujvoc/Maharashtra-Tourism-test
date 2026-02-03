<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;
    protected $fillable = ['country_id','name','code','is_active'];

    public function country() { return $this->belongsTo(Country::class); }
    public function districts() { return $this->hasMany(District::class); }
}
