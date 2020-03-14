<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'receipts';

    public function journal()
    {
        return $this->hasMany('App\GeneralJournal', 'id_receipt');
    }
}
