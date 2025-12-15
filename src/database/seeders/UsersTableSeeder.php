<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'=>'sample',
                'email'=>'hoge@example.com',
                'password'=>Hash::make('hoge1234'),
            ],
            [
                'name'=>'sample2',
                'email'=>'test@example.com',
                'password'=>Hash::make('test1234'),
            ],
        ]);
    }
}
