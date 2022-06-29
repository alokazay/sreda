<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Userseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (User::find(1) == null) {
            User::insert([
                [
                    'id' => 1,
                    'name' => 'Admin',
                    'phone' => '111111111',
                    'group_id' => 1,
                    'password' => Hash::make('1111'),
                    'activation' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }

    }
}
