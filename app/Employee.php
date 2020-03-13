<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    public function company()
    {
        return $this->belongsTo('App\Companies', 'id_company');
    }

    public function business()
    {
        return $this->belongsTo('App\Business', 'id_business');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }
    
}
