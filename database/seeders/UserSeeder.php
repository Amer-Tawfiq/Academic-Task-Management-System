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
        User::create([
            'name' => 'دكتور أحمد',
            'email' => 'doctor@test.com',
            'password' => Hash::make('123456'),
            'role_id' => 1,
            'department_id' => 1,
        ]);

        User::create([
            'name' => 'رئيس القسم',
            'email' => 'head@test.com',
            'password' => Hash::make('123456'),
            'role_id' => 2,
            'department_id' => 1,
        ]);

        User::create([
            'name' => 'العميد',
            'email' => 'dean@test.com',
            'password' => Hash::make('123456'),
            'role_id' => 3,
            'department_id' => 1,
        ]);
    }
}
