<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'receipts';

    public function journal()
    {
        return $this->belongsTo('App\GeneralJournal', 'id_receipt');
    }
}
