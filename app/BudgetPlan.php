<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetPlan extends Model
{
    protected $table = 'budget_plan';

    public function budget_account()
    {
        return $this->belongsTo('App\BudgetAccount', 'id_budget_account');
    }

    public function realization()
    {
        return $this->hasOne('App\BudgetPlanRealization', 'id_budget_plan');
    }
}
