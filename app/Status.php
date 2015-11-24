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

}
