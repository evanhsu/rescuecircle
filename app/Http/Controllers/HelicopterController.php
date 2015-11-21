<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helicopter;


class HelicopterController extends Controller
{
    public function __construct()
    {
        // Use the 'CapitalizeTailnumber' middleware to make sure that all
        // tailnumbers extracted from URLs are converted to all caps before
        // they reach the controller logic.
        $this->middleware('capitalizeTailnumber');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $helicopters = Helicopter::orderBy('tailnumber','asc')->get();

        $request->session()->flash('active_menubutton','helicopters'); // Tell the menubar which button to highlight
        return view('helicopters.index')->with('helicopters',$helicopters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function show($tailnumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($tailnumber)
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
    public function update(Request $request, $tailnumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($tailnumber)
    {
        //
    }

    public function releaseFromCrew(Request $request, $tailnumber) {
        // Disassociate the specified helicopter with this Crew (set crew_id = null) if the current user has authorization

        $heli = Helicopter::where('tailnumber',$tailnumber)->first();
        //echo ("Heli:".$heli.".");

        if(empty($heli)) {
            // Helicopter not found. Nothing to release. Consider this success.
            return response()->json(['status' => 'success']);
        }
        else {
            // Make sure the current user is authorized to release this helicopter
            // Also make sure that this 'release' request was sent from the 'Edit Crew' form of the Crew that currently owns the helicopter
            $user = Auth::user();
            $requesting_crew = $request->input('sent-from-crew');
            $affected_crew = $heli->crew_id;

            if($user->isAdminForCrew($heli->crew_id) && ($requesting_crew == $affected_crew)) {
                if($heli->release()) return response()->json(['status' => 'success']);
                else abort(500); //Something prevented the helicopter from being released
            }
            else {
                // Unauthorized
                abort(401, "Unauthorized");
            }
        }

    }

    /**
     * Show the most recent Status for this Helicopter
     */
    public function showCurrentStatus($tailnumber) {

        $helicopter = Helicopter::findOrFail($tailnumber);

        // Make sure this user is authorized...
        if(Auth::user()->cannot('actAsAdminForCrew', $helicopter->crew_id)) {
            // The current user does not have permission to perform admin functions for this crew
            return redirect()->back()->withErrors("You're not authorized to access that helicopter !");
        }
        // Authorization complete - continue...
        return "Showing most recent Status for Helicopter ".$tailnumber;
    }

    /**
     * Display the Helicopter Status update form
     * Note: this form POSTS its response to the StatusController
     */
    public function newStatus($tailnumber) {
        
        $helicopter = Helicopter::findOrFail($tailnumber);
        // Make sure this user is authorized...
        if(Auth::user()->cannot('actAsAdminForCrew', $helicopter->crew_id)) {
            // The current user does not have permission to perform admin functions for this crew
            return redirect()->back()->withErrors("You're not authorized to access that helicopter!");
        }
        // Authorization complete - continue...

        // Display the status update form
        //return "Helicopter Status update form: Helicopter ".$tailnumber;
        return view('helicopters.new_status')->with("helicopter",$helicopter);
    }
}

