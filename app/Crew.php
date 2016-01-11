<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;
use App\Aircraft;
use App\Status;

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
    protected $fillable = [ 'name', 
                            'address_street1', 
                            'address_street2', 
                            'address_city', 
                            'address_state', 
                            'address_zip', 
                            'phone', 
                            'fax', 
                            'logo_filename',
                            'statusable_type'];

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
    public function aircrafts() {
        // return $this->hasMany(Aircraft::class);
        // return $this->hasMany(Aircraft::class);
        if($this->is_an_aircraft_crew()) {
            $classname = $this->statusable_type;
            return $this->hasMany($classname);
        }
        else {
            return false;
        }
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
        $status = $this->statuses()->orderBy('created_at','desc')->first();

        if(is_null($status)) {
            return new Status;
        }
        else return $status;
    }

    public function statusable_type_plain() {
        // Returns the name of the Class that this Crew's Status Updates belongs to, without any namespacing.
        //   i.e. If this Crew's statuses belong to a Shorthaulhelicopter, then:
        //           $this->statusable_type == "App\Shorthaulhelicopter"
        //        and
        //           $this->statusable_type_plain() == "shorthaulhelicopter"

        if ($pos = strrpos($this->statusable_type, '\\')) {
            return strtolower(substr($this->statusable_type, $pos + 1));
        }
        else {
            return strtolower($this->statusable_type);
        }
    }

    public function is_an_aircraft_crew() {
        // Check to see if this Crew's 'statusable_type' is a class that inherits from the Aircraft class.
        // $classname = "App\\".ucfirst($this->statusable_type);
        $classname = $this->statusable_type;
        $instance = new $classname;
        if($instance instanceof Aircraft) {
            $result = true;
        }
        else {
            $result = false;
        }
        unset($instance);
        return $result;
    }

    public function is_not_an_aircraft_crew() {
        return !$this->is_an_aircraft_crew();
    }

    public function crew_id() {
        // This is simply an alias for crew->id to provide a consistent notation for querying the crew id for all resource types
        return $this->id;
    }

    public function resource_type() {
        // Returns a human-friendly text string that describes this crew's fire resource type (i.e. Short Haul Crew or Hotshot Crew)
        switch($this->statusable_type_plain()) {
            case "shorthaulhelicopter":
                return "Short Haul";
                break;
            case "crew":
                return "Hotshots";
                break;
            case "rappelhelicopter":
                return "Rappel";
                break;
            case "smokejumperairplane":
                return "Smokejumpers";
                break;
            default:
                return "Unknown";
                break;
        }
    }

    public function freshness() {
        // Check the timestamp of the most recent update for this Crew.
        // Return 'fresh', 'stale', 'expired', or 'missing' depending on age thresholds.
        //
        // NOTE: if this function is called on a Crew that has Helicopters, the freshness verb will
        //       refer to the length of time that has passed since ANY of this Crew's helicopters were updated.

        $max_fresh_age = config('app.hours_until_updates_go_stale');
        $expiration_age= config('app.days_until_updates_expire') * 24; // Converted to hours

        $now = Carbon::now();
        $last_status = $this->status();
        if(is_null($last_status->id)) $freshness = "missing"; // No Status has ever been created for this Crew
        else {
            $last_update = $last_status->created_at;
            $age_hours = $now->diffInHours($last_update);  // The number of hours between NOW and the last update
            
            if($age_hours <= $max_fresh_age) $freshness = "fresh";
            elseif(($age_hours > $max_fresh_age) && ($age_hours < $expiration_age)) $freshness = "stale";
            else $freshness = "expired";
        }

        return $freshness;
    }

    public function is_fresh() {
        if($this->freshness() == "fresh") return true;
        else return false;
    }

    public function is_stale() {
        if($this->freshness() == "stale") return true;
        else return false;
    }

    public function is_expired() {
        if($this->freshness() == "expired") return true;
        else return false;
    }

    public function has_no_updates() {
        if($this->freshness() == "missing") return true;
        else return false;
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
