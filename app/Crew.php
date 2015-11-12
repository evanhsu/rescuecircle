<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    protected $fillable = ['name', 'street1', 'street2', 'city', 'state', 'zip', 'phone', 'fax', 'logo_filename'];

    /**
     * The attributes excluded from the models JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
