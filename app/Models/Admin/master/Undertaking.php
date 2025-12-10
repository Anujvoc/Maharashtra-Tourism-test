<?php

namespace App\Models\Admin\master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Undertaking extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

}
