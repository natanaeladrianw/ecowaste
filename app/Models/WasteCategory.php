<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'waste_type',
        'description',
        'points_per_kg',
        'color',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'points_per_kg' => 'integer',
    ];

    // Relationships
    public function wastes()
    {
        return $this->hasMany(Waste::class, 'category_id');
    }
}
