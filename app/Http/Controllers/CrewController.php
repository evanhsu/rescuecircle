<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

Class CrewController extends Controller {


    public function status() {

        // Display the status update form
        return "Crew Status update form";
    } // End showLogin()

    public function getCrews(Request $request) {
        return "List of all crews";
    }
}