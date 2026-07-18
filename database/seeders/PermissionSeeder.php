<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $permissions = [
             // Users
            'manage users',

            // Tables
            'manage tables',

            // Categories
            'manage categories',

            // Menu Items
            'manage menu items',

            // Modifiers
            'manage modifiers',

            // Orders
            'create orders',
            'view orders',
            'update orders',
            'cancel orders',

            // Order Items
            'manage order items',

            // Payments
            'process payments',
            'view payments',

            // Inventory
            'manage inventory',
            'view inventory',

            // Discounts
            'apply discounts',
            'void orders',

            // Activity Logs
            'view activity logs',

            // Notifications
            'manage notifications',

            // Settings
            'manage settings',

            // Transactions
            'view transactions',

            // Reports
            'view reports',

            'manage permissions'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
