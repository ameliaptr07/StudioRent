<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'capacity',
        'price_per_hour',
        'location',
        'assigned_manager_id',
        'status',
    ];

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'studio_addons')
            ->withTimestamps();
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'studio_features')
            ->withTimestamps();
    }
}
