<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'email' => 'guest@example.com',
            'password' => Hash::make('guest123'),
            'role' => 'guest',
        ]);

        User::create([
            'email' => 'staff@example.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);

        User::create([
            'email' => 'headstaff@example.com',
            'password' => Hash::make('headstaff123'),
            'role' => 'head_staff',
        ]);
    }
}
