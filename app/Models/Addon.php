<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    public function studios()
    {
        return $this->belongsToMany(Studio::class, 'studio_addons')
            ->withTimestamps();
    }
}
