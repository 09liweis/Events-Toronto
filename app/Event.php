<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function categories() {
        return $this->belongsToMany('App\Category')->select('categories.id', 'name');
    }
}
