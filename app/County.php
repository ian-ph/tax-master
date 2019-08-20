<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function state()
    {
        return $this->belongsTo('App\State');
    }
}
