<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds - Create default warehouse
     */
    public function run(): void
    {
        $warehouses = [
            [
                'code' => 'WH-MAIN',
                'name' => 'Main Warehouse',
                'address' => 'Jl. Raya Industri No. 123',
                'city' => 'Jakarta',
                'state' => 'DKI Jakarta',
                'country' => 'Indonesia',
                'postal_code' => '12345',
                'phone' => '+62 21 1234567',
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'code' => 'WH-BRANCH',
                'name' => 'Branch Warehouse',
                'address' => 'Jl. Branch No. 456',
                'city' => 'Bandung',
                'state' => 'West Java',
                'country' => 'Indonesia',
                'postal_code' => '40123',
                'phone' => '+62 22 7654321',
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::updateOrCreate(
                ['code' => $warehouse['code']],
                $warehouse
            );
        }

        $this->command->info('Warehouses seeded successfully!');
    }
}
