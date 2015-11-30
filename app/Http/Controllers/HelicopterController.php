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
    public function newStatus(Request $request, $tailnumber) {
        
        $helicopter = Helicopter::where('tailnumber','=', $tailnumber)->first();
        if(is_null($helicopter)) return "Helicopter not found";

        // Make sure this user is authorized...
        if(Auth::user()->cannot('actAsAdminForCrew', $helicopter->crew_id)) {
            // The current user does not have permission to perform admin functions for this crew
            return redirect()->back()->withErrors("You're not authorized to access that helicopter!");
        }
        // Authorization complete - continue...

        // Retrieve the other Helicopters that are owned by the same Crew (to build a navigation menu)
        $crew_helicopters = Helicopter::where('crew_id',$helicopter->crew_id)->orderBy('tailnumber')->get();

        // Retrieve the most recent status update to prepopulate the form (returns a 'new Status' if none exist)
        $last_status = $helicopter->status();

        // Convert the lat and lon from decimal-degrees into decimal-minutes
        // MOVE THIS FUNCTIONALITY INTO A COORDINATES CLASS
        if(!empty($last_status->latitude)) {
            $sign = $last_status->latitude >= 0 ? 1 : -1; // Keep track of whether the latitude is positive or negative
            $last_status->latitude_deg = floor(abs($last_status->latitude)) * $sign;
            $last_status->latitude_min = round((abs($last_status->latitude) - $last_status->latitude_deg) * 60.0, 4);

        } else {
            $last_status->latitude_deg = "";
            $last_status->latitude_min = "";
        }

        if(!empty($last_status->longitude)) {
            $sign = $last_status->longitude >= 0 ? 1 : -1; // Keep track of whether the longitude is positive or negative
            $last_status->longitude_deg = floor(abs($last_status->longitude)) * $sign * -1; // Convert to 'West-positive' reference
            $last_status->longitude_min = round((abs($last_status->longitude) - $last_status->longitude_deg) * 60.0, 4);
        } else {
            $last_status->longitude_deg = "";
            $last_status->longitude_min = "";
        }

        // Display the status update form
        if(Auth::user()->isGlobalAdmin()) {
            $request->session()->flash('active_menubutton','helicopters'); // Tell the menubar which button to highlight
        }
        else {
            $request->session()->flash('active_menubutton','status'); // Tell the menubar which button to highlight
        }
        return view('helicopters.new_status')->with("helicopter",$helicopter)->with("helicopters",$crew_helicopters)->with("status",$last_status);

        // return var_dump($helicopter);
        // return var_dump($last_status);
    }
}

