<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
        $users = json_decode(File::get(database_path('json/users.json')), true);
        $chambas = json_decode(File::get(database_path('json/chambas.json')), true);
        $images = json_decode(File::get(database_path('json/images.json')), true);

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'phone_number' => $user['phone_number'],
                'about_me' => $user['about_me'],
                'postal_code' => $user['postal_code'],
                'city' => $user['city'],
                'street' => $user['street'],
                'slug' => $user['slug'],
                'password' => Hash::make($user['password']),
                'role' => $user['role']
            ]);
        }

        foreach ($chambas as $chamba) {
            DB::table('chambas')->insert([
                'title' => $chamba['title'],
                'description' => $chamba['description'],
                'slug' => $chamba['slug'],
                'worker_id' => $chamba['worker_id'],
                'job_id' => $chamba['job_id'],
            ]);
        }

        foreach ($images as $image) {
            DB::table('images')->insert([
                'user_id' => $image['user_id'],
                'image' => $image['image'],
                'alt' => $image['alt'],
                'path' => $image['path'],
            ]);
        }
    }
}
