<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Purchasing\Models\Vendor;

class PurchasingSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            [
                'name' => 'ABC Supplies Inc.',
                'contact_person' => 'John Smith',
                'email' => 'john@abcsupplies.com',
                'phone' => '+1-555-0101',
                'mobile' => '+1-555-0102',
                'website' => 'https://abcsupplies.com',
                'tax_id' => 'TAX-ABC-001',
                'payment_terms' => 'Net 30',
                'credit_limit' => 50000.00,
                'address' => '123 Industrial Blvd',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001',
                'is_active' => true,
            ],
            [
                'name' => 'Global Tech Solutions',
                'contact_person' => 'Sarah Johnson',
                'email' => 'sarah@globaltech.com',
                'phone' => '+1-555-0201',
                'mobile' => '+1-555-0202',
                'website' => 'https://globaltech.com',
                'tax_id' => 'TAX-GTS-002',
                'payment_terms' => 'Net 45',
                'credit_limit' => 75000.00,
                'address' => '456 Tech Park Dr',
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '94102',
                'is_active' => true,
            ],
            [
                'name' => 'Premium Materials Co.',
                'contact_person' => 'Michael Brown',
                'email' => 'michael@premiummaterials.com',
                'phone' => '+1-555-0301',
                'mobile' => '+1-555-0302',
                'website' => 'https://premiummaterials.com',
                'tax_id' => 'TAX-PMC-003',
                'payment_terms' => 'Net 60',
                'credit_limit' => 100000.00,
                'address' => '789 Commerce St',
                'city' => 'Chicago',
                'state' => 'IL',
                'country' => 'USA',
                'postal_code' => '60601',
                'is_active' => true,
            ],
            [
                'name' => 'Eastern Imports Ltd.',
                'contact_person' => 'Lisa Chen',
                'email' => 'lisa@easternimports.com',
                'phone' => '+1-555-0401',
                'mobile' => '+1-555-0402',
                'tax_id' => 'TAX-EIL-004',
                'payment_terms' => 'Net 30',
                'credit_limit' => 60000.00,
                'address' => '321 Harbor View',
                'city' => 'Seattle',
                'state' => 'WA',
                'country' => 'USA',
                'postal_code' => '98101',
                'is_active' => true,
            ],
            [
                'name' => 'Industrial Equipment Partners',
                'contact_person' => 'David Wilson',
                'email' => 'david@industrialequip.com',
                'phone' => '+1-555-0501',
                'mobile' => '+1-555-0502',
                'website' => 'https://industrialequip.com',
                'tax_id' => 'TAX-IEP-005',
                'payment_terms' => 'Net 45',
                'credit_limit' => 85000.00,
                'address' => '555 Manufacturing Way',
                'city' => 'Detroit',
                'state' => 'MI',
                'country' => 'USA',
                'postal_code' => '48201',
                'is_active' => true,
            ],
        ];

        foreach ($vendors as $vendorData) {
            Vendor::create($vendorData);
        }

        $this->command->info('✓ Purchasing module seeded successfully!');
        $this->command->info('  - Created ' . Vendor::count() . ' vendors');
    }
}
