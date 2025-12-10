<?php

namespace App\Models\Admin\master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TermsAndCondition extends Model
{
    use HasFactory;
    protected $fillable = [
        'form_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function form()
    {
        return $this->belongsTo(\App\Models\Admin\ApplicationForm::class, 'form_id');
    }
}
