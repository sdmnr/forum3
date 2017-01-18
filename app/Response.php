<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function rlikes()
    {
        return $this->hasMany('App\Rlike');
    }
}
