<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core & Security
            EnhancedRolesAndPermissionsSeeder::class,
            
            // Finance Module - Chart of Accounts
            ChartOfAccountsSeeder::class,
            
            // Inventory Module - Master Data
            WarehouseSeeder::class,
            InventoryMasterDataSeeder::class,
            
            // Sales & Purchasing - Sample Data
            SampleCustomersSeeder::class,
            SampleVendorsSeeder::class,
            
            // Production (if exists)
            // ProductionSeeder::class,
        ]);

        $this->command->info('✅ All seeders completed successfully!');
        $this->command->info('🎉 Next ERP is ready for production use!');
    }
}
