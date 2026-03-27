<?php

namespace App\Models\Admin\master\Adventure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdventureActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'adventure_activity_categories_id',
        'slug',
        'name'
    ];

    public function category()
    {
        return $this->belongsTo(AdventureActivityCategory::class, 'adventure_activity_categories_id');
    }
}
