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

        // Require this user to have certain permission before allowing access
        $this->middleware('hasPermission:crew_admin', ['only' => ['status']]);
        $this->middleware('hasPermission:global_admin', ['only' => ['getCrews',
                                                                    'create',
                                                                    'destroy']]);
    }

    public function status($id) {

        // Display the status update form
        return "Crew Status update form: Crew #".$id;
    } // End showLogin()

    public function getCrews(Request $request) {
        return "List of all crews";
        //print_r(session('flash'));
    }
}