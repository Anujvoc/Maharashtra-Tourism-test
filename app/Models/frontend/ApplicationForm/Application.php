<?php

namespace App\Models\frontend\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class Application extends Model
{
    protected $fillable = ['user_id','application_form_id',
    'slug_id','status','current_step','registration_id','is_apply','submitted_at'];
public function user(){ return $this->belongsTo(User::class); }
public function applicant(){ return $this->hasOne(ApplicantDetail::class); }
public function property(){ return $this->hasOne(PropertyDetail::class); }
public function accommodation(){ return $this->hasOne(Accommodation::class); }
public function facilities(){ return $this->hasOne(Facility::class); }
public function photos(){ return $this->hasOne(PhotosSignature::class); }
public function enclosures(){ return $this->hasOne(Enclosure::class); }
public function documents(){ return $this->hasMany(Document::class); }


public function getProgressAttribute(){
$count = 0; $steps = 7; // Applicant, Property, Accommodation, Facilities, Photo/Sign, Enclosures
foreach (['applicant','property','accommodation','facilities','photos','documents','enclosures'] as $rel){
if ($this->{$rel}) $count++;
}
return [ 'done'=>$count, 'total'=>$steps ];
}
protected $casts = [
    'submitted_at' => 'datetime',
];
}
