<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class District extends Model
{
    use SoftDeletes;
    protected $fillable = ['state_id','name','is_active'];

    public function state() { return $this->belongsTo(State::class); }
    public function country() { return $this->hasOneThrough(Country::class, State::class, 'id', 'id', 'state_id', 'country_id'); }
    
}
