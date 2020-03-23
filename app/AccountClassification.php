<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountClassification extends Model
{
    protected $table = 'account_classifications';

    public function account()
    {
        return $this->hasMany('App\Account', 'id_classification')->orderby('account_code');
    }
    
    public function parent()
    {
        return $this->belongsTo('App\AccountParent', 'id_parent');
    }
}
