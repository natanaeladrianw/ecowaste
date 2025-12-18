<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'target_value',
        'current_value',
        'is_completed',
        'completed_at',
        'source_id',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'target_value' => 'integer',
        'current_value' => 'integer',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
