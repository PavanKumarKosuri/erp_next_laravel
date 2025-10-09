<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Purchasing\Models\Vendor;

class SampleVendorsSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run(): void
    {
        $vendors = [
            [
                'code' => 'VEN-00001',
                'name' => 'PT Supplier Utama',
                'contact_person' => 'Ahmad Surya',
                'email' => 'procurement@supplier-utama.com',
                'phone' => '+62 21 98765432',
                'mobile' => '+62 817 6543 2109',
                'address' => 'Jl. Gatot Subroto No. 321',
                'city' => 'Jakarta',
                'state' => 'DKI Jakarta',
                'country' => 'Indonesia',
                'postal_code' => '12930',
                'payment_terms' => 30,
                'credit_limit' => 100000000,
                'is_active' => true,
            ],
            [
                'code' => 'VEN-00002',
                'name' => 'CV Material Supply',
                'contact_person' => 'Siti Nurhaliza',
                'email' => 'sales@materialsupply.com',
                'phone' => '+62 22 11223344',
                'mobile' => '+62 818 9988 7766',
                'address' => 'Jl. Diponegoro No. 654',
                'city' => 'Bandung',
                'state' => 'West Java',
                'country' => 'Indonesia',
                'postal_code' => '40115',
                'payment_terms' => 45,
                'credit_limit' => 75000000,
                'is_active' => true,
            ],
            [
                'code' => 'VEN-00003',
                'name' => 'UD Bahan Bangunan',
                'contact_person' => 'Budi Santoso',
                'email' => 'info@bahanbangunan.com',
                'phone' => '+62 31 44556677',
                'mobile' => '+62 819 3322 1100',
                'address' => 'Jl. Tunjungan No. 987',
                'city' => 'Surabaya',
                'state' => 'East Java',
                'country' => 'Indonesia',
                'postal_code' => '60275',
                'payment_terms' => 30,
                'credit_limit' => 60000000,
                'is_active' => true,
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::updateOrCreate(
                ['code' => $vendor['code']],
                $vendor
            );
        }

        $this->command->info('Sample vendors seeded successfully!');
    }
}
