<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id', 'name',
    ];

    public function country() {
        return $this->belongsTo('App\Country');
    }

    public function cities() {
        return $this->hasMany('App\City');
    }
}
