<?php

namespace Database\Factories;

use App\Models\License;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<License>
 */
class LicenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => 'License-'.Str::random(16),
            'ip' => '',
            'expires_at' => now()->addDays(fake()->numberBetween(1, 30)),
            'status' => 'Active',
            'expireable' => true,
        ];
    }
}
