<?php

namespace Database\Factories;

use App\Models\Studio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Studio>
 */
class StudioFactory extends Factory
{
    protected $model = Studio::class;

    public function definition(): array
    {
        return [
            'name'           => 'Studio ' . fake()->word(),
            'description'    => fake()->sentence(),
            'capacity'       => fake()->numberBetween(1, 10),
            'price_per_hour' => 100000, // disamain dengan skenario di test
            // 'status' TIDAK diset di sini
            // Biar database pakai nilai default dari migration (yang sudah sesuai CHECK constraint)
        ];
    }
}
