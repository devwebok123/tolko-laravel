<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [[
            'name'           => 'UserName',
            'email'          => 'user@mail.com',
            'password'       => Hash::make('password'),
        ]];
        User::insert($users);
    }
}
