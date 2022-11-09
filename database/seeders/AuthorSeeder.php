<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Author::create([
            'uuid' => Str::uuid(),
            'code' => 'DIM',
            'name' => 'Dhimas'
        ]);
    }
}
