<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';

    public function classification()
    {
        return $this->belongsTo('App\AccountClassification', 'id_classification')->orderby('classification_code');
    }

    public function initialBalance()
    {
        return $this->hasMany('App\InitialBalance', 'id_account')->orderby('date');;
    }

    public function journal()
    {
        return $this->hasMany('App\GeneralJournal', 'id_account');
    }

    public function messages()
    {
        return [
            'input_codeAccount.notIn' => 'A title is required',
        ];
    }

}
