<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolePermissionLists = [
            'admin',
            'staff',
            'student'
        ];

        foreach ($rolePermissionLists as $list) {
            Permission::updateOrCreate(
                ['name' => $list],
                ['name' => $list],
            );
        }

        foreach ($rolePermissionLists as $list) {
            Role::updateOrCreate(
                ['name' => $list],
                ['name' => $list],
            );
        }
    }
}
