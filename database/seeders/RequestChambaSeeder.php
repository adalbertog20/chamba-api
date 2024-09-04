<?php

namespace Database\Seeders;

use App\Models\RequestChamba;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestChambaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requestChambas = RequestChamba::factory(3)->create([
            'status' => 'done'
        ]);
        RequestChamba::factory(2)->create([
            'status' => 'pending'
        ]);

        foreach ($requestChambas as $rc) {
            Review::factory()->create([
                'request_chamba_id' => $rc->id,
                'chamba_id' => $rc->chamba_id,
                'client_id' => $rc->client_id,
                'worker_id' => $rc->worker_id,
            ]);
        }
    }
}
