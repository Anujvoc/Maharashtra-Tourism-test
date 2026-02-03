<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class ApplicationForm extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Use slug in URLs and route-model binding
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (blank($model->slug)) {
                $base = trim($model->name) !== '' ? Str::slug($model->name) : Str::random(8);
                $model->slug = static::generateUniqueSlug($base);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $base = trim($model->name) !== '' ? Str::slug($model->name) : Str::random(8);
                $model->slug = static::generateUniqueSlug($base, $model->id);
            }
        });
    }


    protected static function generateUniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base;
        $i = 1;

        while (static::query()
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
