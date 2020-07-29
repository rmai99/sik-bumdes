<?php

use Illuminate\Database\Seeder;
use App\BudgetPlan;

class BudgetPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plan = BudgetPlan::create([
            'id_budget_account' => '1',
            'amount' => 100000,
            'date' => '2020-07-01',
        ],
        [
            'id_budget_account' => '2',
            'amount' => 200000,
            'date' => '2020-07-01',
        ],
        [
            'id_budget_account' => '3',
            'amount' => 300000,
            'date' => '2020-07-01',
        ],
        [
            'id_budget_account' => '4',
            'amount' => 100000,
            'date' => '2020-07-01',
        ],
        [
            'id_budget_account' => '5',
            'amount' => 200000,
            'date' => '2020-07-01',
        ],
        [
            'id_budget_account' => '6',
            'amount' => 300000,
            'date' => '2020-07-01',
        ],
        [
            'id_budget_account' => '7',
            'amount' => 100000,
            'date' => '2020-07-01',
        ],
        [
            'id_budget_account' => '8',
            'amount' => 200000,
            'date' => '2020-07-01',
        ],
        [
            'id_budget_account' => '9',
            'amount' => 300000,
            'date' => '2020-07-01',
        ],
        [
            'id_budget_account' => '10',
            'amount' => 100000,
            'date' => '2020-07-01',
        ],
        [
            'id_budget_account' => '11',
            'amount' => 200000,
            'date' => '2020-07-01',
        ],
        [
            'id_budget_account' => '12',
            'amount' => 300000,
            'date' => '2020-07-01',
        ],
        [
            'id_budget_account' => '13',
            'amount' => 100000,
            'date' => '2020-07-01',
        ]);
    }
}
