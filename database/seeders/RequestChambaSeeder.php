<?php

namespace Database\Seeders;

use App\Models\Chamba;
use App\Models\Chat;
use App\Models\Message;
use App\Models\RequestChamba;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RequestChambaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $worker = User::factory()->create(['role' => 1]);
        $client = User::factory()->create(['role' => 0]);

        $chamba = Chamba::factory()->create([
            'worker_id' => $worker->id,
        ]);

        $requestChamba = RequestChamba::factory()->create([
            'client_id' => $client->id,
            'worker_id' => $worker->id,
            'chamba_id' => $chamba->id,
            'status' => 'accepted',
        ]);
        $chat = Chat::create([
            'request_chamba_id' => $requestChamba->id,
            'client_id' => $client->id,
            'worker_id' => $worker->id,
            'uuid' => Str::uuid(),
        ]);
        Message::factory(5)->create([
            'chat_id' => $chat->id,
            'user_id' => $client->id,
        ]);
        Message::factory(5)->create([
            'chat_id' => $chat->id,
            'user_id' => $worker->id,
        ]);
    }
}
