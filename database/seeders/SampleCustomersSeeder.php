<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Sales\Models\Customer;

class SampleCustomersSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run(): void
    {
        $customers = [
            [
                'customer_code' => 'CUST-00001',
                'name' => 'PT Maju Jaya',
                'email' => 'info@majujaya.com',
                'phone' => '+62 21 12345678',
                'mobile' => '+62 812 3456 7890',
                'address' => 'Jl. Sudirman No. 123',
                'city' => 'Jakarta',
                'state' => 'DKI Jakarta',
                'country' => 'Indonesia',
                'postal_code' => '12190',
                'credit_limit' => 50000000,
                'payment_terms' => 30,
                'is_active' => true,
            ],
            [
                'customer_code' => 'CUST-00002',
                'name' => 'CV Sejahtera Abadi',
                'email' => 'contact@sejahtera.com',
                'phone' => '+62 22 87654321',
                'mobile' => '+62 813 9876 5432',
                'address' => 'Jl. Asia Afrika No. 456',
                'city' => 'Bandung',
                'state' => 'West Java',
                'country' => 'Indonesia',
                'postal_code' => '40111',
                'credit_limit' => 30000000,
                'payment_terms' => 30,
                'is_active' => true,
            ],
            [
                'customer_code' => 'CUST-00003',
                'name' => 'UD Berkah Jaya',
                'email' => 'admin@berkahjaya.com',
                'phone' => '+62 31 55667788',
                'mobile' => '+62 815 4433 2211',
                'address' => 'Jl. Basuki Rahmat No. 789',
                'city' => 'Surabaya',
                'state' => 'East Java',
                'country' => 'Indonesia',
                'postal_code' => '60271',
                'credit_limit' => 40000000,
                'payment_terms' => 45,
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['customer_code' => $customer['customer_code']],
                $customer
            );
        }

        $this->command->info('Sample customers seeded successfully!');
    }
}
