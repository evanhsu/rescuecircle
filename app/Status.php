<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class status extends Model
{
    // This is a polymorphic model that is used to track the movements of different types of firefighting resources.
    // A Status can belong to either a Crew or a Helicopter, as defined by Status->

    // Explicitly define the database table, since 'status' has an awkward plural form
    protected $table = 'statuses';

    public function statusable() {
    	return $this->morphTo();	// Allow multiple other Models to claim a relationship to this model
    }

}
