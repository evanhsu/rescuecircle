<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Crew extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'crews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'address_street1', 'address_street2', 'address_city', 'address_state', 'address_zip', 'phone', 'fax', 'logo_filename'];

    /**
     * The attributes excluded from the models JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Define relationships to other Eloquent models
     *
     */
    public function helicopters() {
        //return $this->hasMany('App/Helicopter');
    }

    public function users() {
        return $this->hasMany(User::class);
    }


    /**
     * Getters and Setters
     *
     */
    public function get($key) {
        if(in_array($key, $this->fillable)) {
            return $this->$key;
        }
        else {
            return nil;
        }
    }

    public function set($key,$value) {
        if(in_array($key, $this->fillable)) {
            $this->$key = $value;
        }
        else {
            return false;
        }
    }

}
