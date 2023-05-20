<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            [ 'name' => 'Fiction'               ],
            [ 'name' => 'Horror'                ],
            [ 'name' => 'Action and Adventure'  ],
            [ 'name' => 'Classics'              ],
            [ 'name' => 'Fantasy'               ],
            [ 'name' => 'Suspense and Thrillers'],
            [ 'name' => 'Comic Book or Graphic Novel'],
            [ 'name' => 'Detective and Mystery'      ],
        ];

        DB::table('genres')->insert($data);

    }
}
