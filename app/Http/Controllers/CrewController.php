<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Class CrewController extends Controller {
    public function __construct()
    {
        // Use the 'auth' middleware to require users to log in
        // This applies to ALL actions within this controller
        $this->middleware('auth');
    }

    public function status($crew_id) {

        // Display the status update form
        return "Crew Status update form: Crew #".$crew_id;
        // print_r(session('flash'));
    } // End showLogin()

    public function getCrews(Request $request) {
        return "List of all crews";
        //print_r(session('flash'));
    }
}