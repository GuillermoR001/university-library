<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'title' => 'The Student',
                'author' => 'Jhon Katzenbach',
                'published_year' => 2015,
                'genre_id' => Genre::where('name', 'Suspense and Thrillers')->first()->id,
                'stock' => 8,
            ],
            [
                'title' => 'The Teacher',
                'author' => 'Jhon Katzenbach',
                'published_year' => 2015,
                'genre_id' => Genre::where('name', 'Suspense and Thrillers')->first()->id,
                'stock' => 10,
            ],
            [
                'title' => 'IT',
                'author' => 'Stephen King',
                'published_year' => 2000,
                'genre_id' => Genre::where('name', 'Horror')->first()->id,
                'stock' => 8,
            ],
            [
                'title' => 'Dracula',
                'author' => 'Bram Stocker',
                'published_year' => 1935,
                'genre_id' => Genre::where('name', 'Horror')->first()->id,
                'stock' => 8,
            ],
            [
                'title' => 'The Call Of Cthulhu',
                'author' => 'H.P Lovecraft',
                'published_year' => 1980,
                'genre_id' => Genre::where('name', 'Horror')->first()->id,
                'stock' => 12,
            ],
        ];

        DB::table('books')->insert($data);

    }
}
