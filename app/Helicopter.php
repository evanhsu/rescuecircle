<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Crew;
use App\User;

class Helicopter extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'helicopters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tailnumber','model','crew_id'];

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
    public function crew() {
    	// Each HELICOPTER belongs to a CREW (the HELICOPTER model contains a 'crew_id' foreign index)
        return $this->belongsTo(Crew::class);
    }

    public function users() {
    	// Allow access to the Users who have permission to edit this Helicopter
        return $this->hasManyThrough(User::class, Crew::class);
    }

    public function release() {
    	// Disassociate this helicopter from it's Crew (set Helicopter->crew_id to NULL)
    	return $this->set('crew_id',null);
    }

    private function differences(Helicopter $helicopter) {
    	// Compare the current Helicopter model to the input $helicopter.
    	// Return an array of the object properties that differ, if any differences exist.
    	// Return NULL if the two models are identical.

		$properties_to_compare = array('tailnumber','model','crew_id');
		$differences = array();

		foreach($properties_to_compare as $p) {
    		if($this->$p != $helicopter->$p) {
    			$differences[] = $p;
    		}
	    }

	    if(sizeof($differences) == 0) {
	    	return null;
	    }
	    else {
	    	return $differences;
	    }
    }

    public function updateIfChanged($attributes) {
    	// Compare the attributes of $this Helicopter to the array of $attributes passed in.
    	// If they match, don't update this instance.
    	// If there are differences, update $this with the values from $attributes.
    	//
    	// Return FALSE if there are errors
    	// Return TRUE otherwise
    	//    Note: this function will return TRUE even if no update is performed, as long as there are no errors.


    	$proposed_helicopter = new Helicopter($attributes);
    	if(is_null($this->differences($proposed_helicopter))) {
    		// This helicopter already matches the proposed attributes. Do nothing.
    		return true;
    	}
		// This helicopter instance needs to be updated
		elseif($this->update($attributes)) {
			// The model was updated
			return true;
		}
		else {
			// There was an error while updating the model
			return false;
		}
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
            return true;
        }
        else {
            return false;
        }
    }
}
