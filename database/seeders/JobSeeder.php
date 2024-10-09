<?php

namespace Database\Seeders;

use App\Models\Job;
use File;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/json/jobs.json");
        $jobs = json_decode($json);

        foreach ($jobs as $key => $value) {
            Job::create([
                'name' => $value->name,
                'slug' => $value->slug,
                'description' => $value->description,
            ]);
        }
    }
}
