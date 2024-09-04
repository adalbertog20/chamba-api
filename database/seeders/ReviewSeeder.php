<?php

namespace Database\Seeders;

use App\Models\Chamba;
use App\Models\RequestChamba;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doneChambas = RequestChamba::where('status', 'done')->get();
        $clients = User::where('role', '0')->get();
        $workers = User::where('role', '1')->get();

        foreach ($doneChambas as $chamba) {
            Review::factory()->create([
                'request_chamba_id' => $chamba->id,
                'chamba_id' => $chamba->chamba_id,
                'client_id' => $chamba->client_id,
                'worker_id' => $chamba->worker_id,
                'rating' => rand(1, 5),
                'comment' => faker()->text()
            ]);
        }
    }
}
