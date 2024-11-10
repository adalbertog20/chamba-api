<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\RequestChamba;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            JobSeeder::class,
            ChambaSeeder::class,
            RequestChambaSeeder::class,
        ]);

        DB::table('users')->insert([
            'name' => 'Duver',
            'email' => 'duver@gmail.com',
            'slug' => 'duver',
            'password' => Hash::make('duver')
        ]);
        DB::table('users')->insert([
            'name' => 'Jonathan',
            'email' => 'jona@gmail.com',
            'slug' => 'jonathan',
            'password' => Hash::make('jona')
        ]);
        DB::table('users')->insert([
            'name' => 'Adalberto',
            'email' => 'adal@gmail.com',
            'slug' => 'adalberto',
            'password' => Hash::make('adalberto')
        ]);
        DB::table('users')->insert([
            'name' => 'Worker1',
            'email' => 'worker1@gmail.com',
            'slug' => 'worker1',
            'password' => Hash::make('worker1'),
            'role' => '1'
        ]);
        DB::table('users')->insert([
            'name' => 'Worker2',
            'email' => 'worker2@gmail.com',
            'slug' => 'worker2',
            'password' => Hash::make('worker2'),
            'role' => '1'
        ]);
    }
}
