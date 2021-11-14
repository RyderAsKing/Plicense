<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;


class LicenseFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'key' => 'License-' . Str::random(16),
            'ip' => '192.168.0.1',
            'expires_at' => now()->addDays(rand(1, 5)),
            'status' => 'Active',
        ];
    }
}
