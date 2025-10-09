<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Role Management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            
            // Permission Management
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
            
            // HR Module
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            
            // General
            'access admin panel',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $staffRole = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);

        // Assign permissions to roles
        $superAdminRole->givePermissionTo(Permission::all());
        
        $adminRole->givePermissionTo([
            'view users', 'create users', 'edit users',
            'view roles', 'view permissions',
            'view employees', 'create employees', 'edit employees', 'delete employees',
            'access admin panel',
        ]);
        
        $managerRole->givePermissionTo([
            'view users',
            'view employees', 'create employees', 'edit employees',
            'access admin panel',
        ]);
        
        $staffRole->givePermissionTo([
            'view employees',
            'access admin panel',
        ]);

        // Create a super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@next-erp.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        
        $superAdmin->assignRole('Super Admin');

        $this->command->info('Roles and permissions seeded successfully!');
        $this->command->info('Super Admin created: admin@next-erp.com / password');
    }
}
