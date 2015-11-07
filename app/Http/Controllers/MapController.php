<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

Class MapController extends Controller {


    public function getMap() {

        // Display the main map page.
        // The menubar will display different links depending on whether this user is logged in or not

        return View::make('/')->with('');
    } // End getMap()

    public function getMapJSON() {

        /**
        *   This will return a JSON array of all helicopters with their attached location data & contact info
        *
        *   $data = $helicopter->jsonDump();
        *   $data = {
        *               "helicopters" : {
        *                   "tailnumber"    : "N12345",
        *                   "crew_id"       : "1",
        *                   "staffing_emt"  : "8",
        *                   "latitude"      : "42.67893",
        *                   "longitude"     : "-123.89409"
        *               }    
        *           };
        *
        */
        return response()->json(array('helicopters' => array(array( 'tailnumber'=> "N12345", 
                                                            'crew_id'   => '1',
                                                            'latitude'  => '42.67893',
                                                            'longitude' => '-123.89409'))));
    } // End getMapJSON()
}