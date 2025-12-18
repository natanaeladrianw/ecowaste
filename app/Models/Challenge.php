<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'target_amount',
        'target_unit',
        'target_category_id',
        'points_reward',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'target_amount' => 'integer',
        'points_reward' => 'integer',
    ];

    // Relationships
    public function targetCategory()
    {
        return $this->belongsTo(WasteCategory::class, 'target_category_id');
    }
}
