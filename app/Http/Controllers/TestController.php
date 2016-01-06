<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Form;
use App\Crew;
use App\User;
use App\Aircraft;
use App\Status;

use App\ArcServer;
use Log;


// This Class is for DEVELOPMENT  USE to test various components of the website.
// The entire class will be disabled when the environment variable "APP_ENV" is set to "production"
if(env("APP_ENV") != "production") {


class TestController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to require users to log in
        // This applies to ALL actions within this controller

        // $this->middleware('auth');
    }

    public function testtoken(Request $request) {
        // Test the ArcGIS Server token to see if this site is able to log in to the ArcGIS Server
        ArcServer::testToken();
        return;
    }


    public function findFeature() {
        // This method tests the functionality of the 'findFeature()' method of the ArcServer class.
        // It will grab the first Status in the database and use it to retrieve the corresponding feature from the ArcGIS Server.
        // This function also provides a sample of how to implement the ArcServer::findFeature() method in production.

        $status = Status::first();
        $response = ArcServer::findFeature($status);

        echo "Looking for tailnumber: ".$status->statusable_name."<br />\n";
        
        if($response === false) {
            echo "An error prevented this query from completing";
            // Log the error, if desired
        }
        else {
            // Success!
            //The ArcGIS Server responded with an array of OBJECT_IDs, as expected (the array could be empty empty though)
            echo "OBJECT_IDs => [".implode(",",$response)."]";
        }
        return;
    }


    public function deleteFeature($id) {
        // INPUT $id    The OBJECTID of a feature on the ArcGIS server

        $response = ArcServer::deleteFeature($id);
        echo "Attempting to delete feature with OBJECTID = ".$id."<br />\n";

        if($response === false) {
            echo "An error prevented this query from completing";
            // Log the error, if desired
        }
        else {
            // Success!
            //The ArcGIS Server responded with an array of OBJECT_IDs, as expected (the array could be empty though)
            echo var_dump($response);
        }
        return;
    }
}// END class()


} // END if(env("APP_ENV") != "production")