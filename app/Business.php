<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $table = 'businesses';

    public function parent()
    {
        return $this->hasMany('App\AccountParent', 'id_business ');
    }

    public function company()
    {
        return $this->belongsTo('App\Companies', 'id_company');
    }

    public function session()
    {
        return $this->hasMany('App\BusinessSession', 'id_business');
    }

}
