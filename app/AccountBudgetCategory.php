<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountBudgetCategory extends Model
{
    protected $table = 'budget_account_category';

    public function budget_account()
    {
        return $this->hasMany('App\BudgetAccount', 'id_category');
    }
    
}
