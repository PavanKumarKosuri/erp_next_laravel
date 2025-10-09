<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Finance\Models\Account;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing accounts
        Account::query()->delete();

        // Assets
        $assets = Account::create([
            'code' => '1000',
            'name' => 'Assets',
            'type' => 'asset',
            'description' => 'All asset accounts',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1100',
            'name' => 'Current Assets',
            'type' => 'asset',
            'parent_id' => $assets->id,
            'is_active' => true,
        ]);

        $currentAssets = Account::where('code', '1100')->first();

        Account::create([
            'code' => '1110',
            'name' => 'Cash',
            'type' => 'asset',
            'parent_id' => $currentAssets->id,
            'description' => 'Cash on hand and in banks',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1120',
            'name' => 'Accounts Receivable',
            'type' => 'asset',
            'parent_id' => $currentAssets->id,
            'description' => 'Money owed by customers',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '1130',
            'name' => 'Inventory',
            'type' => 'asset',
            'parent_id' => $currentAssets->id,
            'description' => 'Stock of goods',
            'is_active' => true,
        ]);

        // Liabilities
        $liabilities = Account::create([
            'code' => '2000',
            'name' => 'Liabilities',
            'type' => 'liability',
            'description' => 'All liability accounts',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '2100',
            'name' => 'Current Liabilities',
            'type' => 'liability',
            'parent_id' => $liabilities->id,
            'is_active' => true,
        ]);

        $currentLiabilities = Account::where('code', '2100')->first();

        Account::create([
            'code' => '2110',
            'name' => 'Accounts Payable',
            'type' => 'liability',
            'parent_id' => $currentLiabilities->id,
            'description' => 'Money owed to suppliers',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '2120',
            'name' => 'Salaries Payable',
            'type' => 'liability',
            'parent_id' => $currentLiabilities->id,
            'description' => 'Unpaid employee salaries',
            'is_active' => true,
        ]);

        // Equity
        $equity = Account::create([
            'code' => '3000',
            'name' => 'Equity',
            'type' => 'equity',
            'description' => 'Owner\'s equity',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '3100',
            'name' => 'Owner\'s Capital',
            'type' => 'equity',
            'parent_id' => $equity->id,
            'description' => 'Capital invested by owner',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '3200',
            'name' => 'Retained Earnings',
            'type' => 'equity',
            'parent_id' => $equity->id,
            'description' => 'Accumulated profits',
            'is_active' => true,
        ]);

        // Revenue
        $revenue = Account::create([
            'code' => '4000',
            'name' => 'Revenue',
            'type' => 'revenue',
            'description' => 'All revenue accounts',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '4100',
            'name' => 'Sales Revenue',
            'type' => 'revenue',
            'parent_id' => $revenue->id,
            'description' => 'Revenue from sales',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '4200',
            'name' => 'Service Revenue',
            'type' => 'revenue',
            'parent_id' => $revenue->id,
            'description' => 'Revenue from services',
            'is_active' => true,
        ]);

        // Expenses
        $expenses = Account::create([
            'code' => '5000',
            'name' => 'Expenses',
            'type' => 'expense',
            'description' => 'All expense accounts',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '5100',
            'name' => 'Cost of Goods Sold',
            'type' => 'expense',
            'parent_id' => $expenses->id,
            'description' => 'Direct costs of goods sold',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '5200',
            'name' => 'Operating Expenses',
            'type' => 'expense',
            'parent_id' => $expenses->id,
            'is_active' => true,
        ]);

        $opEx = Account::where('code', '5200')->first();

        Account::create([
            'code' => '5210',
            'name' => 'Salaries Expense',
            'type' => 'expense',
            'parent_id' => $opEx->id,
            'description' => 'Employee salaries',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '5220',
            'name' => 'Rent Expense',
            'type' => 'expense',
            'parent_id' => $opEx->id,
            'description' => 'Office and facility rent',
            'is_active' => true,
        ]);

        Account::create([
            'code' => '5230',
            'name' => 'Utilities Expense',
            'type' => 'expense',
            'parent_id' => $opEx->id,
            'description' => 'Electricity, water, internet',
            'is_active' => true,
        ]);

        $this->command->info('✓ Finance module seeded successfully!');
        $this->command->info('  - Created ' . Account::count() . ' chart of accounts');
    }
}
