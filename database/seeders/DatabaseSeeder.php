<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        ]);
        $this->call([
            ChambaSeeder::class
        ]);
        DB::table('users')->insert([
            'name' => 'Duver',
            'email' => 'duver@gmail.com',
            'password' => Hash::make('duver')
        ]);
        DB::table('users')->insert([
            'name' => 'Jonathan',
            'email' => 'jona@gmail.com',
            'password' => Hash::make('jona')
        ]);
        DB::table('users')->insert([
            'name' => 'Adalberto',
            'email' => 'adal@gmail.com',
            'password' => Hash::make('adalberto')
        ]);
        DB::table('users')->insert([
            'name' => 'Worker1',
            'email' => 'worker1@gmail.com',
            'password' => Hash::make('worker1'),
            'role' => '1'
        ]);
        DB::table('users')->insert([
            'name' => 'Worker2',
            'email' => 'worker2@gmail.com',
            'password' => Hash::make('worker2'),
            'role' => '1'
        ]);
    }
}
