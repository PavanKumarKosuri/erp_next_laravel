<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EnhancedRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds - Create comprehensive RBAC
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions grouped by module
        $permissions = [
            // Sales Module
            'view_customers', 'create_customers', 'edit_customers', 'delete_customers',
            'view_quotations', 'create_quotations', 'edit_quotations', 'delete_quotations',
            'view_sales_orders', 'create_sales_orders', 'edit_sales_orders', 'delete_sales_orders',
            'view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices',
            'view_payments', 'create_payments', 'edit_payments', 'delete_payments',

            // Purchasing Module
            'view_vendors', 'create_vendors', 'edit_vendors', 'delete_vendors',
            'view_purchase_orders', 'create_purchase_orders', 'edit_purchase_orders', 'delete_purchase_orders',
            'view_goods_receipts', 'create_goods_receipts', 'edit_goods_receipts', 'delete_goods_receipts',

            // Inventory Module
            'view_products', 'create_products', 'edit_products', 'delete_products',
            'view_categories', 'create_categories', 'edit_categories', 'delete_categories',
            'view_warehouses', 'create_warehouses', 'edit_warehouses', 'delete_warehouses',
            'view_stock_movements', 'create_stock_movements', 'edit_stock_movements', 'delete_stock_movements',

            // Finance Module
            'view_accounts', 'create_accounts', 'edit_accounts', 'delete_accounts',
            'view_journals', 'create_journals', 'edit_journals', 'delete_journals',
            'view_financial_reports', 'export_financial_reports',

            // HR Module
            'view_employees', 'create_employees', 'edit_employees', 'delete_employees',
            'view_payroll', 'process_payroll', 'approve_payroll',

            // System/Core Module
            'view_activity_logs', 'view_company_settings', 'edit_company_settings',
            'manage_users', 'manage_roles', 'manage_permissions',

            // Reports Module
            'view_sales_reports', 'view_purchase_reports', 'view_inventory_reports',
            'export_reports',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles with specific permissions

        // 1. Super Admin - All permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // 2. Manager - Most permissions except system management
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $managerPermissions = Permission::where('name', 'not like', 'manage_%')
            ->where('name', 'not like', 'delete_%')
            ->get();
        $manager->givePermissionTo($managerPermissions);

        // 3. Sales Staff - Sales module only
        $salesStaff = Role::firstOrCreate(['name' => 'sales_staff']);
        $salesPermissions = Permission::where('name', 'like', '%customers%')
            ->orWhere('name', 'like', '%quotations%')
            ->orWhere('name', 'like', '%sales_orders%')
            ->orWhere('name', 'like', '%invoices%')
            ->orWhere('name', 'like', '%payments%')
            ->get();
        $salesStaff->givePermissionTo($salesPermissions);

        // 4. Purchasing Staff - Purchasing and Inventory view
        $purchasingStaff = Role::firstOrCreate(['name' => 'purchasing_staff']);
        $purchasingPermissions = Permission::where('name', 'like', '%vendors%')
            ->orWhere('name', 'like', '%purchase_orders%')
            ->orWhere('name', 'like', '%goods_receipts%')
            ->orWhere('name', 'like', 'view_products')
            ->orWhere('name', 'like', 'view_stock%')
            ->get();
        $purchasingStaff->givePermissionTo($purchasingPermissions);

        // 5. Warehouse Staff - Inventory module
        $warehouseStaff = Role::firstOrCreate(['name' => 'warehouse_staff']);
        $warehousePermissions = Permission::where('name', 'like', '%products%')
            ->orWhere('name', 'like', '%warehouses%')
            ->orWhere('name', 'like', '%stock%')
            ->get();
        $warehouseStaff->givePermissionTo($warehousePermissions);

        // 6. Accountant - Finance module
        $accountant = Role::firstOrCreate(['name' => 'accountant']);
        $accountantPermissions = Permission::where('name', 'like', '%accounts%')
            ->orWhere('name', 'like', '%journals%')
            ->orWhere('name', 'like', '%financial%')
            ->orWhere('name', 'like', '%reports%')
            ->get();
        $accountant->givePermissionTo($accountantPermissions);

        // 7. HR Staff - HR module
        $hrStaff = Role::firstOrCreate(['name' => 'hr_staff']);
        $hrPermissions = Permission::where('name', 'like', '%employees%')
            ->orWhere('name', 'like', '%payroll%')
            ->get();
        $hrStaff->givePermissionTo($hrPermissions);

        // 8. Viewer - Read-only access
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewerPermissions = Permission::where('name', 'like', 'view_%')->get();
        $viewer->givePermissionTo($viewerPermissions);

        $this->command->info('Enhanced roles and permissions seeded successfully!');
        $this->command->info('Created ' . count($permissions) . ' permissions and 8 roles.');
    }
}
