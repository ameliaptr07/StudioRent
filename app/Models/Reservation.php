<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'studio_id',
        'start_time',
        'end_time',
        'status',
        'checkin_code',
        'checked_in_at',
        'total_price',
    ];

    protected $casts = [
        'start_time'    => 'datetime',
        'end_time'      => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function addons(): BelongsToMany
    {
        // pivot table: reservation_addons (reservation_id, addon_id, qty)
        return $this->belongsToMany(Addon::class, 'reservation_addons')
            ->withPivot('qty')
            ->withTimestamps();
    }
}
