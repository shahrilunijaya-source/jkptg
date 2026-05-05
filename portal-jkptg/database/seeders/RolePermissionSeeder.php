<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage users',
            'manage pages',
            'manage services',
            'manage forms',
            'manage news',
            'manage chatbot',
            'view audit log',
            'view dashboard',
            'submit application',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $roles = [
            'super-admin' => $permissions,
            'editor' => ['manage pages', 'manage services', 'manage forms', 'manage news', 'manage chatbot', 'view dashboard'],
            'viewer' => ['view dashboard', 'view audit log'],
            'citizen' => ['submit application'],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
