<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CrewController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to require users to log in
        // This applies to ALL actions within this controller
        $this->middleware('auth');

        // Require the current user to have certain permission before allowing access
        $this->middleware('hasPermission:crew_admin,true', ['only' => ['status']]);
        $this->middleware('hasPermission:global_admin', ['only' => ['getCrews',
                                                                    'create',
                                                                    'destroy']]);
    }

    /**
     * Display the Crew Status update form
     * Note: this form POSTS its response to the CrewStatusController
     */
    public function status($id) {

        // Display the status update form
        return "Crew Status update form: Crew #".$id;
    }


    /**
     * Display a listing of all crews (requires a global_admin user)
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        return "Index of all crews";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return "Create a new Crew - form";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
