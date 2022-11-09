<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Book::create([
            'uuid' => Str::uuid(),
            'code' => 'TMK',
            'author_id' => 1,
            'publisher_id' => 1,
            'name' => 'Tutorial Menjadi Kaya'
        ]);
    }
}
