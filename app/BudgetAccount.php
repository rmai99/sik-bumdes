<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetAccount extends Model
{
    
    protected $table = 'budget_account';
    
    public function company()
    {
        return $this->belongsTo('App\Companies', 'id_company');
    }

    public function category()
    {
        return $this->belongsTo('App\AccountBudgetCategory', 'id_category');
    }

    public function budget_plan()
    {
        return $this->hasOne('App\BudgetPlan', 'id_budget_account');
    }
}
