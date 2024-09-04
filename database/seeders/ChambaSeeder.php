<?php

namespace Database\Seeders;

use App\Models\Chamba;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChambaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Chamba::factory(5)->create();
    }
}
