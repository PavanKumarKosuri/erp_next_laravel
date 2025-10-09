<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Finance\Models\Account;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds - Create Standard Chart of Accounts
     */
    public function run(): void
    {
        $accounts = [
            // ASSETS (1000-1999)
            ['code' => '1000', 'name' => 'Cash and Bank', 'type' => 'asset', 'parent_id' => null],
            ['code' => '1100', 'name' => 'Petty Cash', 'type' => 'asset', 'parent_id' => null],
            ['code' => '1200', 'name' => 'Accounts Receivable', 'type' => 'asset', 'parent_id' => null],
            ['code' => '1300', 'name' => 'Inventory', 'type' => 'asset', 'parent_id' => null],
            ['code' => '1400', 'name' => 'Prepaid Expenses', 'type' => 'asset', 'parent_id' => null],
            ['code' => '1500', 'name' => 'Tax Receivable', 'type' => 'asset', 'parent_id' => null],
            ['code' => '1600', 'name' => 'Fixed Assets', 'type' => 'asset', 'parent_id' => null],
            ['code' => '1610', 'name' => 'Equipment', 'type' => 'asset', 'parent_id' => null],
            ['code' => '1620', 'name' => 'Furniture & Fixtures', 'type' => 'asset', 'parent_id' => null],
            ['code' => '1700', 'name' => 'Accumulated Depreciation', 'type' => 'asset', 'parent_id' => null],

            // LIABILITIES (2000-2999)
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability', 'parent_id' => null],
            ['code' => '2100', 'name' => 'Tax Payable', 'type' => 'liability', 'parent_id' => null],
            ['code' => '2200', 'name' => 'Salaries Payable', 'type' => 'liability', 'parent_id' => null],
            ['code' => '2300', 'name' => 'Loans Payable', 'type' => 'liability', 'parent_id' => null],
            ['code' => '2400', 'name' => 'Accrued Expenses', 'type' => 'liability', 'parent_id' => null],

            // EQUITY (3000-3999)
            ['code' => '3000', 'name' => 'Owner\'s Capital', 'type' => 'equity', 'parent_id' => null],
            ['code' => '3100', 'name' => 'Owner\'s Drawings', 'type' => 'equity', 'parent_id' => null],
            ['code' => '3200', 'name' => 'Retained Earnings', 'type' => 'equity', 'parent_id' => null],
            ['code' => '3300', 'name' => 'Current Year Earnings', 'type' => 'equity', 'parent_id' => null],

            // REVENUE (4000-4999)
            ['code' => '4000', 'name' => 'Sales Revenue', 'type' => 'revenue', 'parent_id' => null],
            ['code' => '4100', 'name' => 'Service Revenue', 'type' => 'revenue', 'parent_id' => null],
            ['code' => '4200', 'name' => 'Other Income', 'type' => 'revenue', 'parent_id' => null],
            ['code' => '4300', 'name' => 'Interest Income', 'type' => 'revenue', 'parent_id' => null],

            // EXPENSES (5000-5999)
            ['code' => '5000', 'name' => 'Cost of Goods Sold', 'type' => 'expense', 'parent_id' => null],
            ['code' => '5100', 'name' => 'Salaries & Wages', 'type' => 'expense', 'parent_id' => null],
            ['code' => '5200', 'name' => 'Rent Expense', 'type' => 'expense', 'parent_id' => null],
            ['code' => '5300', 'name' => 'Utilities Expense', 'type' => 'expense', 'parent_id' => null],
            ['code' => '5400', 'name' => 'Office Supplies', 'type' => 'expense', 'parent_id' => null],
            ['code' => '5500', 'name' => 'Depreciation Expense', 'type' => 'expense', 'parent_id' => null],
            ['code' => '5600', 'name' => 'Marketing & Advertising', 'type' => 'expense', 'parent_id' => null],
            ['code' => '5700', 'name' => 'Insurance Expense', 'type' => 'expense', 'parent_id' => null],
            ['code' => '5800', 'name' => 'Bank Charges', 'type' => 'expense', 'parent_id' => null],
            ['code' => '5900', 'name' => 'Miscellaneous Expenses', 'type' => 'expense', 'parent_id' => null],
        ];

        foreach ($accounts as $account) {
            Account::updateOrCreate(
                ['code' => $account['code']],
                [
                    'name' => $account['name'],
                    'type' => $account['type'],
                    'parent_id' => $account['parent_id'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Chart of Accounts seeded successfully!');
    }
}
