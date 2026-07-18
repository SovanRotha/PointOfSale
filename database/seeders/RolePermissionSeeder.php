<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {

        // Admin gets everything
        $admin = Role::findByName('admin');

        $admin->givePermissionTo([
            'manage users',
            'manage tables',
            'manage categories',
            'manage menu items',
            'manage modifiers',
            'create orders',
            'view orders',
            'update orders',
            'cancel orders',
            'manage order items',
            'process payments',
            'view payments',
            'manage inventory',
            'view inventory',
            'apply discounts',
            'void orders',
            'view activity logs',
            'manage notifications',
            'manage settings',
            'view transactions',
            'view reports',
            'manage permissions'
            
        ]);


        // Cashier
        $cashier = Role::findByName('cashier');

        $cashier->givePermissionTo([
            'create orders',
            'view orders',
            'process payments',
        ]);


        // Chef
        $chef = Role::findByName('chef');

        $chef->givePermissionTo([
            'view orders',
        ]);


        // Waiter
        $waiter = Role::findByName('waiter');

        $waiter->givePermissionTo([
            'create orders',
            'view orders',
        ]);


        // Manager
        $manager = Role::findByName('manager');

        $manager->givePermissionTo([
            'view reports',
            'manage inventory',
            'apply discounts',
            'view transactions',
        ]);
    }
}