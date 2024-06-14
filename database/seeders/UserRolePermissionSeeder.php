<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        Permission::create(['name' => 'view-role']);
        Permission::create(['name' => 'create-role']);
        Permission::create(['name' => 'update-role']);
        Permission::create(['name' => 'delete-role']);

        Permission::create(['name' => 'view-permission']);
        Permission::create(['name' => 'create-permission']);
        Permission::create(['name' => 'update-permission']);
        Permission::create(['name' => 'delete-permission']);

        Permission::create(['name' => 'view-user']);
        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'update-user']);
        Permission::create(['name' => 'delete-user']);


        // Create Roles
        $superAdminRole = Role::create(['name' => 'super-admin']); //as super-admin
        $adminRole = Role::create(['name' => 'admin']);
        $employeeRole = Role::create(['name' => 'employee']);

        // Lets give all permission to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        // Let's give few permissions to admin role.
        $adminRole->givePermissionTo(['create-role', 'view-role', 'update-role']);
        $adminRole->givePermissionTo(['create-permission', 'view-permission']);
        $adminRole->givePermissionTo(['create-user', 'view-user', 'update-user']);


        // Let's Create User and assign Role to it.

        $superAdminUser = User::firstOrCreate([
                    'email' => 'superadmin@cdd.edu.ph',
                ], [
                    'name' => 'Super Admin',
                    'email' => 'superadmin@cdd.edu.ph',
                    'password' => Hash::make ('password'),
                ]);

        $superAdminUser->assignRole($superAdminRole);


        $adminUser = User::firstOrCreate([
                            'email' => 'admin@cdd.edu.ph'
                        ], [
                            'name' => 'Admin',
                            'email' => 'admin@cdd.edu.ph',
                            'password' => Hash::make ('password'),
                        ]);

        $adminUser->assignRole($adminRole);


        $employeeUser = User::firstOrCreate([
                            'email' => 'employee@cdd.edu.ph',
                        ], [
                            'name' => 'Employee',
                            'email' => 'employee@cdd.edu.ph',
                            'password' => Hash::make('password'),
                        ]);

        $employeeUser->assignRole($employeeRole);
    }
}
