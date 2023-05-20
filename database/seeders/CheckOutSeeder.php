<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CheckOutSeeder extends Seeder
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
                'user_id' => 1,
                'book_id' => 1,
                'comments' => null,
                'checkout_date'=> Carbon::now()->format('Y-m-d h:i:s'),
                'return_date',
            ]
        ];

        DB::table('checkouts')->insert($data);
    }
}
