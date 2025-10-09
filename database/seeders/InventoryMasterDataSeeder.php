<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Brand;
use Modules\Inventory\Models\Unit;

class InventoryMasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run(): void
    {
        // Categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic items'],
            ['name' => 'Furniture', 'slug' => 'furniture', 'description' => 'Furniture items'],
            ['name' => 'Office Supplies', 'slug' => 'office-supplies', 'description' => 'Office supplies'],
            ['name' => 'Raw Materials', 'slug' => 'raw-materials', 'description' => 'Raw materials for production'],
            ['name' => 'Finished Goods', 'slug' => 'finished-goods', 'description' => 'Finished products'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // Brands
        $brands = [
            ['name' => 'Generic', 'slug' => 'generic', 'description' => 'Generic brand'],
            ['name' => 'Premium', 'slug' => 'premium', 'description' => 'Premium brand'],
            ['name' => 'Standard', 'slug' => 'standard', 'description' => 'Standard brand'],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => $brand['slug']],
                $brand
            );
        }

        // Units
        $units = [
            ['name' => 'Pieces', 'short_name' => 'PCS'],
            ['name' => 'Kilogram', 'short_name' => 'KG'],
            ['name' => 'Liter', 'short_name' => 'LTR'],
            ['name' => 'Box', 'short_name' => 'BOX'],
            ['name' => 'Set', 'short_name' => 'SET'],
            ['name' => 'Meter', 'short_name' => 'MTR'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(
                ['short_name' => $unit['short_name']],
                $unit
            );
        }

        $this->command->info('Inventory master data seeded successfully!');
    }
}
