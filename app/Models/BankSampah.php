<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSampah extends Model
{
    use HasFactory;

    protected $table = 'bank_sampah';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'operating_hours',
        'latitude',
        'longitude',
        'location_name',
        'description',
        'accepted_categories',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'accepted_categories' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
}
