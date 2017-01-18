<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rlike extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function response()
    {
        return $this->belongsTo('App\Response','response_id');
    }
}
