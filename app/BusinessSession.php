<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessSession extends Model
{
  protected $table = 'business_session';

  protected $fillable = [
      'id_user', 'id_business',
  ];

  public function business()
  {
      return $this->belongsTo('App\Business', 'id_business');
  }

  public function user()
  {
      return $this->belongsTo('App\User', 'id_user');
  }
}
