<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetPlanRealization extends Model
{
    protected $table = 'budget_plan_realization';

    protected $primaryKey = 'id_budget_plan';

    public function budget_plan()
    {
        return $this->belongsTo('App\BudgetPlan', 'id_budget_plan');
    }
}
