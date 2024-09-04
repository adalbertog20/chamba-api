<?php

namespace Database\Factories;

use App\Models\Chamba;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequestChamba>
 */
class RequestChambaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => User::factory(['role' => 0]),
            'worker_id' => User::factory(['role' => 1]),
            'chamba_id' => Chamba::factory(),
            "message" => $this->faker->sentence(),
            'status' => 'pending',
        ];
    }
}
