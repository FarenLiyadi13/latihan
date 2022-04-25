<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
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
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@gmail.com',
                'password' => Hash::make('admin123'),
                'remember_token' => null,
                'created_at' => date('Y-m-d h:i:s'),
            ],
            [
                'name' => 'Jane',
                'email' => 'jane@gmail.com',
                'password' => Hash::make('admin123'),
                'remember_token' => null,
                'created_at' => date('Y-m-d h:i:s'),
            ],

        ];
        User::insert($users);
    }
}
