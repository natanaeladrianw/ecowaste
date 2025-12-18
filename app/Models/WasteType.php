<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WasteType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wasteType) {
            if (empty($wasteType->slug)) {
                $wasteType->slug = Str::slug($wasteType->name);
            }
        });

        static::updating(function ($wasteType) {
            if ($wasteType->isDirty('name') && empty($wasteType->slug)) {
                $wasteType->slug = Str::slug($wasteType->name);
            }
        });
    }

    // Relationships
    public function categories()
    {
        return $this->hasMany(WasteCategory::class, 'waste_type', 'slug');
    }
}
