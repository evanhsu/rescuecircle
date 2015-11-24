<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Form;
use App\Crew;
use App\Helicopter;
use App\Status;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // Accept a form post from either the Helicopter Status Form (route: 'new_status_for_helicopter')
        // or the Crew Status Form (route: 'new_status_for_crew')

        // Determine whether this is a status update for a Helicopter or a Crew
        // then store the ID of the Crew that owns this object.
        switch($request->get('statusable_type')) {
            case "helicopter":
                $obj = Helicopter::findOrFail($request->get('statusable_id'));
                $crew_id = $obj->crew_id;
                break;

            case "crew":
                $obj = Crew::findOrFail($request->get('statusable_id'));
                $crew_id = $obj->id;
                break;

            default:
                // The 'statusable_type' from the form is not one of the polymorphic 'statusable' classes.
                // Add the 'morphMany()' function to the desired class to make it statusable.
                return redirect()->back()->with('alert', array('message' => 'Status update failed! This status update is not linked to a statusable entity', 'type' => 'danger'));
        }

        // Make sure current user is authorized
        if(Auth::user()->cannot('actAsAdminForCrew', $crew_id)) {
            // The current user does not have permission to perform admin functions for this crew
            return redirect()->back()->withErrors("You're not authorized to update that crew!");
        }
        // This User is authorized - continue...

        $this->validate($request, [
            'latitude_deg' => 'required',
            'latitude_min' => 'required',
            'longitude_deg' => 'required',
            'longitude_min' => 'required'
            ]);

        $latitude_dd = $this->decMinToDecDeg($request->get('latitude_deg'), $request->get('latitude_min'));
        $longitude_dd = $this->decMinToDecDeg($request->get('longitude_deg'), $request->get('longitude_min'));

        // Form is valid, continue...
        $status = new Status(Input::all());

        // Insert the name of the User who created this Status update (the CURRENT user):
        $status->created_by = Auth::user()->fullname();

        // Insert the lat and lon in decimal-degree format
        $status->latitude = $latitude_dd;
        $status->longitude = $longitude_dd;

        // Change the 'statusable_type' variable to a fully-namespaced class name.
        // i.e. Change 'helicopter' to 'App\Helicopter'. This is required for the Status class to be able to retrieve the correct Helicopter (or Crew).
        $status->statusable_type = "App\\".ucwords($status->statusable_type);

        // Attempt to save
        if($status->save()) {
            return redirect()->back()->with('alert', array('message' => 'Status update saved!', 'type' => 'success'));
        }
        return redirect()->back()->with('alert', array('message' => 'Status update failed!', 'type' => 'danger'));
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

    protected function decMinToDecDeg($deg, $min) {
        // Convert a latitude or longitude from DD MM.MMMM (decimal minutes)
        // to DD.DDDDD (decimal degrees) format
        return round(($deg * 1.0) + ($min / 60.0), 5);
    }
}
