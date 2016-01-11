<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Form;
use Carbon\Carbon;
use DB;
use App\Crew;
use App\Aircraft;
use App\Status;
use App\ArcServer;

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
     * This function responds to AJAX requests from the map to update all resources
     *
     * @return \Illuminate\Http\Response
     */
    public function currentForAllResources()
    {
        // 1. Retrieve the most recent Status for each resource that's been updated within the last 30 days.
        // 2. Package the response into a JSON object and return.

        $max_age = config('days_until_updates_expire');
        $earliest_date = Carbon::now()->subDays($max_age); // The oldest Status that will be displayed

        $resources = DB::table('statuses as newest')
                        ->leftjoin('statuses as newer', function($join) {
                            $join->on('newer.statusable_id','=','newest.statusable_id');
                            $join->on('newer.updated_at','>','newest.updated_at');
                            })
                        ->select('newest.*')
                        ->whereNull('newer.updated_at')
                        ->where('newest.updated_at','>=',$earliest_date)
                        ->get();

        // return response()->json($resources);
        // sleep(4); // Test asynchronous loading on the map view
        return json_encode($resources);
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
        // Accept a form post from either the Aircraft Status Form (route: 'new_status_for_aircraft')
        // or the Crew Status Form (route: 'new_status_for_crew')

        // Determine whether this is a status update for an Aircraft or a Crew
        // then store the ID of the Crew that owns this object.
        $classname = $request->get('statusable_type');
        $obj = $classname::find($request->get('statusable_id'));
        if(!$obj) {
            // The 'statusable_type' from the form is not one of the polymorphic 'statusable' classes.
            // Add the 'morphMany()' function to the desired class to make it statusable.
            return redirect()->back()->with('alert', array('message' => 'Status update failed! This status update is not linked to a statusable entity', 'type' => 'danger'));
        }
        $crew_id = $obj->crew_id();

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
        $longitude_dd = $this->decMinToDecDeg($request->get('longitude_deg'), $request->get('longitude_min')) * -1.0; // Convert to 'Easting' (Western hemisphere is negative)

        // Form is valid, continue...
        $status = new Status(Input::all());

        // Insert the identity of the User who created this Status update (the CURRENT user):
        $status->created_by_name = Auth::user()->fullname();
        $status->created_by_id = Auth::user()->id;

        // Insert the name of the Crew that owns this Status update (if this Status refers to a Crew, then 'crew_name' will be the same as 'statusable_name')
        $status->crew_name = Crew::find($crew_id)->name;

        // Insert the lat and lon in decimal-degree format
        $status->latitude = $latitude_dd;
        $status->longitude = $longitude_dd;

        // Change the 'statusable_type' variable to a fully-namespaced class name (the html form only submits the class name, but not the namespace)
        // i.e. Change 'Shorthaulhelicopter' to 'App\Shorthaulhelicopter'. This is required for the Status class to be able to retrieve the correct Aircraft (or Crew).
        //$status->statusable_type = "App\\".ucwords($status->statusable_type);

        // Build the HTML popup that will be displayed when this feature is clicked
        $status->popupinfo = $this->generatePopup($status);

        // Attempt to save
        if($status->save()) {
            // Changes have been saved to the local database, now initiate an update on the ArcGIS Server...
            $objectids = ArcServer::findFeature($status);
            if($objectids === false) {
                // An error occurred in findFeature() - check 'laravel.log' for details
                return redirect()->back()->with('alert', array('message' => 'Status update was saved locally, but could not be sent to the EGP (findFeature error).', 'type' => 'danger'));
            }
            elseif(!isset($objectids[0]) || ($objectids[0] == '')) {
                // The server responded, but the request feature was not found - add it.
                $result = ArcServer::addFeature($status);
            }
            else {
                // The Feature being updated was found on the ArcGIS server - now update it.
                $objectid = $objectids[0];
                $result = ArcServer::updateFeature($objectid,$status);
            }
            
            // Check the ArcGIS server response to determine if the operation was successful or not.
            if(empty($result->error)) {
                return redirect()->back()->with('alert', array('message' => 'Status update saved!', 'type' => 'success'));
            }
            else {
                return redirect()->back()->with('alert', array('message' => 'Status update was saved locally, but could not be sent to the EGP: '.$result->error, 'type' => 'danger'));
            }
        }
        return redirect()->back()->with('alert', array('message' => 'Status update failed!', 'type' => 'danger'));
    }


    private function generatePopup($status) {
        // Constructs the HTML that will be displayed when this Update Feature is clicked on the map view
        // The HTML string must be stored in this object's 'popupinfo' property, which corresponds directly with a database field
        // that is used by the ArcGIS server to generate the popup for each Feature.
        //
        // This function relies on a Blade view template existing in the /resources/views/map_popups/ folder for each Class that
        // can have a status (e.g. Shorthaulhelicopter.blade.php, etc)
        //
        // All properties of the Status object must be defined before calling this method.

        return view('map_popups.'.$status->statusable_type_plain())->with("status",$status);
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
        return round(($deg * 1.0) + ($min / 60.0), 6);
    }
}
