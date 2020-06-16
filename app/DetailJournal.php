<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailJournal extends Model
{
    protected $table = 'journal_detail';
    protected $orderBy = 'date';
    protected $orderDirection = 'DESC';

    public function journal()
    {
        return $this->hasMany('App\GeneralJournal', 'id_detail');
    }
}
