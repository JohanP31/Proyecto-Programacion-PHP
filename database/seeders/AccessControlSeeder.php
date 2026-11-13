<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AccessControlSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard.view',
            'admin.dashboard.view',
            'products.view',
            'products.admin.view',
            'products.search',
            'products.create',
            'products.update',
            'products.delete',
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'cart.view',
            'cart.add',
            'cart.remove',
            'sales.create',
            'sales.view',
            'sales.view_all',
            'payments.create',
            'payments.store',
        ];

        $permissionIds = collect($permissions)
            ->mapWithKeys(fn (string $permissionName): array => [
                $permissionName => Permission::firstOrCreate(['name' => $permissionName])->id,
            ]);

        $rolePermissions = [
            'Administrador' => $permissions,
            'Cliente' => [
                'dashboard.view',
                'products.view',
                'products.search',
                'cart.view',
                'cart.add',
                'cart.remove',
                'sales.create',
                'sales.view',
                'payments.create',
                'payments.store',
            ],
        ];

        foreach ($rolePermissions as $roleName => $assignedPermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->permissions()->sync($permissionIds->only($assignedPermissions)->values());
        }
    }
}
