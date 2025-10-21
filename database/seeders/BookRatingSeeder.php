<?php

namespace Database\Seeders;

use App\Models\BookRating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        BookRating::factory()->count(500000)->create();
    }
}
