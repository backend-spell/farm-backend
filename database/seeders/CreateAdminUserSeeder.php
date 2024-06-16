<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'status' => 1
            ]);
            $adminRole = Role::create(['name' => 'Admin', 'guard_name' => 'api']);
            $permissions = Permission::pluck('id', 'id')->all();
            $adminRole->syncPermissions($permissions);
            $user->assignRole([$adminRole->id]);

    }
}
