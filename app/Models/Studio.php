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
        'status',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function addons()
    {
        // pivot: studio_addons (studio_id, addon_id, dll.)
        return $this->belongsToMany(Addon::class, 'studio_addons')
            ->withTimestamps();
    }

    public function features()
    {
        // pivot: studio_features (studio_id, feature_id, dll.)
        return $this->belongsToMany(Feature::class, 'studio_features')
            ->withTimestamps();
    }
}
