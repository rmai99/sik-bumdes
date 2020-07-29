<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'id', 'name', 'address', 'phone_number', 'id_user'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }


    public function employees()
    {
        return $this->hasMany('App\Employee', 'id_company');
    }
    
    public function business()
    {
        return $this->hasMany('App\Business', 'id_company');
    }

    public function budget_account()
    {
        return $this->hasMany('App\BudgetAccount', 'id_company');
    }

}
