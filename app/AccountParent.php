<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountParent extends Model
{
    protected $table = 'account_parent';

    public function business()
    {
        return $this->belongsTo('App\Business', 'id_business');
    }

    public function classification()
    {
        return $this->hasMany('App\AccountClassification', 'id_parent')->orderby('classification_code');
    }
}
