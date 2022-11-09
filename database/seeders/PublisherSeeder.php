<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Publisher::create([
            'uuid' => Str::uuid(),
            'code' => 'GRM',
            'name' => 'Gramedia'
        ]);
    }
}
