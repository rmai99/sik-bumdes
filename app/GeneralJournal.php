<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralJournal extends Model
{
    protected $table = 'general_journals';

    public function account()
    {
        return $this->belongsTo('App\Account', 'id_account');
    }

    public function receipt()
    {
        return $this->belongsTo('App\Receipt', 'id_receipt');
    }
}
