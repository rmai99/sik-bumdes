<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InitialBalance extends Model
{
    protected $table = 'initial_balances';

    public function account()
    {
        return $this->belongsTo('App\Account', 'id_account')->orderby('account_code');
    }
}
