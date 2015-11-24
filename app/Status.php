<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // This is a polymorphic model that is used to track the movements of different types of firefighting resources.
    // A Status can belong to either a Crew or a Helicopter, as defined by Status->

    // Explicitly define the database table, since 'status' has an awkward plural form
    protected $table = 'statuses';

/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [	'latitude',
    						'longitude',
    						'staffing_category1',
    						'staffing_value1',
    						'staffing_category2',
    						'staffing_value2',
    						'manager_name',
    						'manager_phone',
    						'comments1',
    						'comments2',
    						'assigned_fire_name',
    						'assigned_fire_number',
    						'assigned_supervisor',
    						'assigned_supervisor_phone',
    						'statusable_name',
    						'statusable_type',
    						'statusable_id',
    						'created_by'];

    /**
     * The attributes excluded from the models JSON form.
     *
     * @var array
     */
    // Consider hiding 'created_by'...
    protected $hidden = [];


    public function statusable() {
    	return $this->morphTo();	// Allow multiple other Models to claim a relationship to this model
    }

    public function statusable_type_plain() {
        // Returns the name of the Class that this Status belongs to, without any namespacing.
        //   i.e. If this Status belongs to a Helicopter, then:
        //           $this->statusable_type == "App\Helicopter"
        //        and
        //           $this->statusable_type_plain() == "helicopter"

        if ($pos = strrpos($this->statusable_type, '\\')) {
            return strtolower(substr($this->statusable_type, $pos + 1));
        }
        else {
            return strtolower($this->statusable_type);
        }
    }

    public function redirectToNewStatus() {
        // Returns the RedirectResponse that should be used to submit a new Status that supercedes $this.
        // For example, if $this is a Status for Helicopter 'N2345', then redirect to "route(status_for_helicopter,'N2345')"

        $route_name = "new_status_for_".$this->statusable_type_plain();
        $parent = $this->statusable;  // The instance of the parent class that owns this Status

        switch($this->statusable_type_plain()) {
            case 'helicopter':
                $route_id = $parent->tailnumber; // Helicopters routes use the tailnumber rather than the ID
                break;

            default:
                $route_id = $parent->id;
                break;
        }

        return redirect()->route($route_name,$route_id);
    }

}
