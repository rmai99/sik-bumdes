<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';

    public function classification()
    {
        return $this->belongsTo('App\AccountClassification', 'id_classification');
    }

    public function initialBalance()
    {
        return $this->hasMany('App\InitialBalance', 'id_account');
    }

    public function journal()
    {
        return $this->hasMany('App\GeneralJournal', 'id_account');
    }

    public function getbyId()
    {
        return $this->db->get($this->_table)->result();
    }
}
