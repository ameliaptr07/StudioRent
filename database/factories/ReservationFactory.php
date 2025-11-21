<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Studio;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        $start = now()->addDays(1)->setTime(10, 0);
        $end   = (clone $start)->addMinutes(90);

        return [
            'user_id'    => User::factory(),
            'studio_id'  => Studio::factory(),
            'start_time' => $start,
            'end_time'   => $end,
            'status'     => 'confirmed',
            'checkin_code' => 'CHK-' . Str::random(8),
            'checked_in_at' => null,
            'total_price'   => 150000,
        ];
    }
}
