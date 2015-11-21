<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Crew;
use App\User;
use App\Auth;

class Helicopter extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'helicopters';
    protected $primaryKey = 'tailnumber'; // The primary key is NOT 'id' !!

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

    public function statuses() {
        // Create a relationship with the polymorphic Status model
        return $this->morphMany('App\Status', 'statusable');
    }

    public function status() {
        // Get the MOST RECENT status for this Helicopter
        return $this->statuses()->orderBy('created_at','desc')->first();
    }

    public function release() {
    	// Disassociate this helicopter from it's Crew (set Helicopter->crew_id to NULL)
    	$this->set('crew_id',null);
        if($this->save()) return true;
        else return false;
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
	    	print_r($differences);
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

        // Convert tailnumber to all caps before storing - Laravel has no way of performing
        // a case-insensitive search within the Eloquent ORM, so we must ensure all tailnumbers use consistent case.
        $attributes['tailnumber'] = strtoupper($attributes['tailnumber']);
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
