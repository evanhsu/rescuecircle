<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Helicopter;

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
        return $this->hasMany(Helicopter::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }

    public function statuses() {
        // Create a relationship with the polymorphic Status model
        return $this->morphMany('App\Status', 'statusable');
    }

    public function status() {
        // Get the MOST RECENT status for this Crew
        return $this->statuses()->orderBy('created_at','desc')->first();
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
