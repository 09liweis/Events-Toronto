<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = array('pivot');
    
    public function events() {
        return $this->belongsToMany('App\Event')->select(array(
            'events.id',
            'events.name',
            'events.address',
            'events.thumbnail',
            'events.start_date',
            'rec_id'));
    }
}
