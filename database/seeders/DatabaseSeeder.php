<?php

namespace Database\Seeders;

use App\Models\User;
use Domain\SharedKernel\Models\Tenant;
use Domain\Income\Models\IncomeSourceType;
use Domain\Expense\Models\ExpenseCategory;
use Domain\Income\Actions\RecordIncomeEntryAction;
use Domain\Expense\Actions\CreateExpenseAction;
use Domain\Goal\Models\FinancialGoal;
use Domain\Debt\Models\DebtAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(RecordIncomeEntryAction $recordIncome, CreateExpenseAction $createExpense): void
    {
        // 1. Create a Primary Tenant
        $tenant = Tenant::create([
            'id' => Str::uuid()->toString(),
            'name' => 'Default Tenant',
            'slug' => 'default-tenant',
            'is_active' => true,
        ]);

        // 2. Create the Primary User
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'), // Demo login
        ]);

        // 3. Create Income Sources
        $salarySource = IncomeSourceType::create([
            'tenant_id' => $tenant->id,
            'name' => 'Salary',
            'slug' => 'salary',
            'icon' => 'briefcase',
            'is_system' => true,
        ]);

        $freelanceSource = IncomeSourceType::create([
            'tenant_id' => $tenant->id,
            'name' => 'Freelance',
            'slug' => 'freelance',
            'icon' => 'laptop',
        ]);

        // 4. Create Expense Categories
        $categories = [
            ['name' => 'Rent/Mortgage', 'slug' => 'rent', 'icon' => 'home'],
            ['name' => 'Groceries', 'slug' => 'groceries', 'icon' => 'shopping-cart'],
            ['name' => 'Utilities', 'slug' => 'utilities', 'icon' => 'bolt'],
            ['name' => 'Entertainment', 'slug' => 'entertainment', 'icon' => 'film'],
            ['name' => 'Dining Out', 'slug' => 'dining', 'icon' => 'utensils'],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[] = ExpenseCategory::create(array_merge($cat, ['tenant_id' => $tenant->id]));
        }

        // 5. Generate 6 Months of Realistic Historical Data
        $startDate = Carbon::now()->subMonths(6)->startOfMonth();
        $endDate = Carbon::now();

        // Iterate month by month
        $currentMonth = $startDate->copy();
        while ($currentMonth <= $endDate) {
            
            // Add Salary for the month
            $recordIncome->execute([
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'source_type_id' => $salarySource->id,
                'amount' => 150000.00, // 1.5L INR
                'currency' => 'INR',
                'income_date' => $currentMonth->copy()->addDays(2)->format('Y-m-d'), // Salary on 2nd
            ]);

            // Add occasional freelance income
            if (rand(1, 3) === 1) {
                $recordIncome->execute([
                    'user_id' => $user->id,
                    'tenant_id' => $tenant->id,
                    'source_type_id' => $freelanceSource->id,
                    'amount' => rand(10000, 40000),
                    'currency' => 'INR',
                    'income_date' => $currentMonth->copy()->addDays(rand(10, 25))->format('Y-m-d'),
                ]);
            }

            // Generate daily random expenses
            $daysInMonth = $currentMonth->daysInMonth;
            // Cap to current day if we are in the current month
            $daysToGenerate = ($currentMonth->isSameMonth(Carbon::now())) ? Carbon::now()->day : $daysInMonth;

            for ($day = 1; $day <= $daysToGenerate; $day++) {
                // Fixed Rent on the 1st
                if ($day === 1) {
                    $createExpense->execute([
                        'user_id' => $user->id,
                        'tenant_id' => $tenant->id,
                        'category_id' => $categoryModels[0]->id, // Rent
                        'title' => 'Monthly Rent',
                        'amount' => 45000.00,
                        'expense_date' => $currentMonth->copy()->addDays($day - 1)->format('Y-m-d'),
                    ]);
                }

                // Random expenses throughout the month (Groceries, Dining, etc.)
                // 30% chance of no expense on a given day to make it realistic
                if (rand(1, 10) <= 7) {
                    $randomCat = $categoryModels[rand(1, 4)]; // Skip rent
                    $amount = match($randomCat->slug) {
                        'groceries' => rand(500, 3500),
                        'utilities' => rand(1000, 5000),
                        'entertainment' => rand(300, 2000),
                        'dining' => rand(500, 2500),
                        default => rand(100, 1000)
                    };

                    $createExpense->execute([
                        'user_id' => $user->id,
                        'tenant_id' => $tenant->id,
                        'category_id' => $randomCat->id,
                        'title' => ucfirst($randomCat->slug) . ' expense',
                        'amount' => $amount,
                        'expense_date' => $currentMonth->copy()->addDays($day - 1)->format('Y-m-d'),
                    ]);
                }
            }

            $currentMonth->addMonth();
        }

        // 6. Create active Goal and Debt
        FinancialGoal::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'name' => 'Emergency Fund',
            'target_amount' => 500000.00,
            'current_amount' => 150000.00,
            'currency' => 'INR',
            'deadline' => Carbon::now()->addYear()->format('Y-m-d'),
            'is_completed' => false,
        ]);

        DebtAccount::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'name' => 'Car Loan',
            'type' => 'personal_loan',
            'principal_amount' => 800000.00,
            'current_balance' => 650000.00,
            'currency' => 'INR',
            'interest_rate' => 9.5,
            'minimum_payment' => 18000.00,
            'due_date_day' => 10,
            'is_paid_off' => false,
        ]);
    }
}
