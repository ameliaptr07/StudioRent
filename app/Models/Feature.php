<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_name',
    ];

    public function studios()
    {
        return $this->belongsToMany(Studio::class, 'studio_features')
            ->withTimestamps();
    }
}
