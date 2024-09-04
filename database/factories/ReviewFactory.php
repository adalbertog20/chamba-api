<?php

namespace Database\Factories;

use App\Models\Chamba;
use App\Models\RequestChamba;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $requestChamba = RequestChamba::factory()->create();
        return [
            'request_chamba_id' => $requestChamba->id,
            'chamba_id' => $requestChamba->chamba_id,
            'client_id' => $requestChamba->client_id,
            'worker_id' => $requestChamba->worker_id,
            'rating' => $this->faker->randomFloat(2, 1, 5),
            'comment' => $this->faker->paragraph,
        ];
    }
}
