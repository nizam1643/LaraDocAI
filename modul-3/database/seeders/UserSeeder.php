<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]
        )->assignRole('admin');

        $student = User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'student@example.com',
                'password' => bcrypt('password'),
            ]
        )->assignRole('student');
    }
}
