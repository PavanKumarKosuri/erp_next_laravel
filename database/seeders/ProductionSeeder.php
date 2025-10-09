<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Production\Models\BillOfMaterial;
use Modules\Production\Models\BomComponent;
use Modules\Inventory\Models\Product;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        // BOM 1: Assembled Laptop
        $laptopProduct = Product::where('name', 'LIKE', '%Laptop%')->first();
        if ($laptopProduct) {
            $laptopBom = BillOfMaterial::create([
                'product_id' => $laptopProduct->id,
                'name' => 'Standard Laptop Assembly',
                'version' => '1.0',
                'quantity' => 1,
                'description' => 'Complete laptop assembly with all components',
                'is_active' => true,
            ]);

            // Get some products for components
            $products = Product::take(5)->get();
            
            if ($products->count() >= 3) {
                BomComponent::create([
                    'bom_id' => $laptopBom->id,
                    'product_id' => $products[0]->id,
                    'quantity' => 1,
                    'unit_cost' => 150.00,
                    'scrap_percentage' => 2,
                    'notes' => 'Motherboard component',
                ]);

                BomComponent::create([
                    'bom_id' => $laptopBom->id,
                    'product_id' => $products[1]->id,
                    'quantity' => 1,
                    'unit_cost' => 80.00,
                    'scrap_percentage' => 1,
                    'notes' => 'Display panel',
                ]);

                BomComponent::create([
                    'bom_id' => $laptopBom->id,
                    'product_id' => $products[2]->id,
                    'quantity' => 2,
                    'unit_cost' => 25.00,
                    'scrap_percentage' => 3,
                    'notes' => 'RAM modules',
                ]);
            }
        }

        // BOM 2: Gift Package
        $giftProduct = Product::where('name', 'LIKE', '%Gift%')->orWhere('name', 'LIKE', '%Package%')->first();
        if (!$giftProduct) {
            $giftProduct = Product::skip(5)->first();
        }

        if ($giftProduct) {
            $giftBom = BillOfMaterial::create([
                'product_id' => $giftProduct->id,
                'name' => 'Deluxe Gift Package',
                'version' => '2.0',
                'quantity' => 10,
                'description' => 'Premium gift package with multiple items',
                'is_active' => true,
            ]);

            $products = Product::skip(6)->take(4)->get();
            
            if ($products->count() >= 3) {
                BomComponent::create([
                    'bom_id' => $giftBom->id,
                    'product_id' => $products[0]->id,
                    'quantity' => 10,
                    'unit_cost' => 5.00,
                    'scrap_percentage' => 5,
                    'notes' => 'Gift box',
                ]);

                BomComponent::create([
                    'bom_id' => $giftBom->id,
                    'product_id' => $products[1]->id,
                    'quantity' => 10,
                    'unit_cost' => 3.50,
                    'scrap_percentage' => 2,
                    'notes' => 'Tissue paper',
                ]);

                BomComponent::create([
                    'bom_id' => $giftBom->id,
                    'product_id' => $products[2]->id,
                    'quantity' => 10,
                    'unit_cost' => 2.00,
                    'scrap_percentage' => 1,
                    'notes' => 'Ribbon and decorations',
                ]);
            }
        }

        // BOM 3: Office Desk Assembly
        $deskProduct = Product::where('name', 'LIKE', '%Desk%')->orWhere('name', 'LIKE', '%Table%')->first();
        if (!$deskProduct) {
            $deskProduct = Product::skip(10)->first();
        }

        if ($deskProduct) {
            $deskBom = BillOfMaterial::create([
                'product_id' => $deskProduct->id,
                'name' => 'Executive Desk Assembly',
                'version' => '1.5',
                'quantity' => 1,
                'description' => 'Complete office desk with hardware',
                'is_active' => true,
            ]);

            $products = Product::skip(11)->take(5)->get();
            
            if ($products->count() >= 4) {
                BomComponent::create([
                    'bom_id' => $deskBom->id,
                    'product_id' => $products[0]->id,
                    'quantity' => 1,
                    'unit_cost' => 120.00,
                    'scrap_percentage' => 3,
                    'notes' => 'Desktop surface',
                ]);

                BomComponent::create([
                    'bom_id' => $deskBom->id,
                    'product_id' => $products[1]->id,
                    'quantity' => 4,
                    'unit_cost' => 15.00,
                    'scrap_percentage' => 2,
                    'notes' => 'Desk legs',
                ]);

                BomComponent::create([
                    'bom_id' => $deskBom->id,
                    'product_id' => $products[2]->id,
                    'quantity' => 1,
                    'unit_cost' => 25.00,
                    'scrap_percentage' => 1,
                    'notes' => 'Hardware kit',
                ]);

                BomComponent::create([
                    'bom_id' => $deskBom->id,
                    'product_id' => $products[3]->id,
                    'quantity' => 2,
                    'unit_cost' => 18.00,
                    'scrap_percentage' => 2,
                    'notes' => 'Drawer units',
                ]);
            }
        }

        $this->command->info('✓ Production module seeded successfully');
        $this->command->info('  - Created ' . BillOfMaterial::count() . ' BOMs');
        $this->command->info('  - Created ' . BomComponent::count() . ' BOM components');
    }
}
