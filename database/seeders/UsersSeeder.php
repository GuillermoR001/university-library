<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
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
                'name' => 'Guillermo',
                'last_name'=> 'Rodriguez',
                'email' => 'ga.rodriguez0997@gmail.com',
                'password' => bcrypt('Ul0001'),
                'user_rol' => UserRoles::STUDENT->value,
            ],
            [
                'name' => 'Antonio',
                'last_name'=> 'Alvarado',
                'email' => 'ga.rodriguez0998@gmail.com',
                'password' => bcrypt('Ul0002'),
                'user_rol' => UserRoles::LIBRARIAN->value,
            ],
        ];

        DB::table('users')->insert($data);
    }
}
