<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'middlename', 'address', 'city_id', 'state_id', 'country_id', 'zip', 'age', 'birthdate', 'date_hired', 'department_id', 'division_id', 'picture'
    ];

    public function city() {
        return $this->belongsTo('App\City');
    }

    public function state() {
        return $this->belongsTo('App\State');
    }

    public function country() {
        return $this->belongsTo('App\Country');
    }

    public function department() {
        return $this->belongsTo('App\Department');
    }

    public function division() {
        return $this->belongsTo('App\Division');
    }
}
