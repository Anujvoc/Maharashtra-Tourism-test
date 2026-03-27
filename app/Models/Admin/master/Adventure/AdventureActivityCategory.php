<?php

namespace App\Models\Admin\master\Adventure;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdventureActivityCategory extends Model
{
    use HasFactory;

    protected $table = 'adventure_activity_categories';

    protected $fillable = [
        'name',
        'slug',
    ];
}
