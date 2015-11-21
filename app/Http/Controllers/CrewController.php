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
use App\Helicopter;

class CrewController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to require users to log in
        // This applies to ALL actions within this controller
        $this->middleware('auth');
    }

    /**
     * Show the most recent Status for this Crew
     */
    public function showCurrentStatus($id) {

        // Make sure this user is authorized...
        if(Auth::user()->cannot('actAsAdminForCrew', $id)) {
            // The current user does not have permission to perform admin functions for this crew
            return redirect()->back()->withErrors("You're not authorized to access that crew!");
        }
        // Authorization complete - continue...
        return "Showing most recent Status for Crew #".$id;
    }


    /**
     * Display the Crew Status update form
     * Note: this form POSTS its response to the StatusController
     */
    public function newStatus($id) {
        // Display the status update form

        // Make sure this user is authorized...
        if(Auth::user()->cannot('actAsAdminForCrew', $id)) {
            // The current user does not have permission to perform admin functions for this crew
            return redirect()->back()->withErrors("You're not authorized to access that crew!");
        }
        // Authorization complete - continue...
        return "Crew Status update form: Crew #".$id;
    }

    /**
     * Display all User Accounts for this Crew
     */
    public function accounts(Request $request, $id) {

        // Make sure this user is authorized...
        if(Auth::user()->cannot('actAsAdminForCrew', $id)) {
            // The current user does not have permission to perform admin functions for this crew
            return redirect()->back()->withErrors("You're not authorized to access that crew!");
        }

        // Authorization complete - continue...
        $crew = Crew::findOrFail($id);
        $users = User::where('crew_id',$id)
                    ->orderBy('firstname', 'asc')
                    ->orderBy('lastname','asc')
                    ->get();

        $request->session()->flash('active_menubutton','accounts'); // Tell the menubar which button to highlight
        return view('crews.accounts', [ 'crew'  => $crew,
                                        'users' => $users ]);
    }

    /**
     * Display a listing of all crews (requires a global_admin user)
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!Auth::user()->isGlobalAdmin()) {
            // Only Global Admins can access this
            return redirect()->back()->withErrors("Unauthorized");
        }

        $crews = Crew::orderBy('name', 'asc')->get();
        $request->session()->flash('active_menubutton','crews'); // Tell the menubar which button to highlight
        return view('crews.index', ['crews' => $crews]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->isGlobalAdmin()) {
            // Only Global Admins can access this
            return redirect()->back()->withErrors("Unauthorized");
        }

        return view('crews.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(!Auth::user()->isGlobalAdmin()) {
            // Only Global Admins can access this
            return redirect()->back()->withErrors("Unauthorized");
        }

        $this->validate($request, [
            'name' => 'required|unique:crews|max:255']);

        // Form is valid, continue...
        $crew = new Crew(Input::all());
        if($crew->save()) {
            return redirect()->route('crews_index');
        }
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
        return "crew.show";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        if($crew = Crew::findorfail($id)) {
            // Make sure this user is authorized...
            if(Auth::user()->cannot('actAsAdminForCrew', $id)) {
                // The current user does not have permission to perform admin functions for this crew
                return redirect()->back()->withErrors("You're not authorized to access that crew!");
            }

            // Authorization complete - continue...
            $request->session()->flash('active_menubutton','identity'); // Tell the menubar which button to highlight
            return view('crews.edit')->with('crew',$crew);
        }
        $errors = new MessageBag(['Crew' => ['That Crew doesn\'t exist.']]);
        //return redirect()->route('not_found')->withErrors($errors);
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
        // Make sure this user is authorized...
        if(Auth::user()->cannot('actAsAdminForCrew', $id)) {
            // The current user does not have permission to perform admin functions for this crew
            return redirect()->back()->withErrors("You're not authorized to access that crew!");
        }

        // Authorization complete - continue...


        // Grab the form input
        $crew_fields = array_except($request->input('crew'), ['helicopters']);
        $helicopter_fields = $request->input('crew')['helicopters'];

        $crew = Crew::find($id);

        // Deal with a logo file upload
        if($request->hasFile('logo')) {
            if($request->file('logo')->isValid()) {
                $filename = "crew_".$id."_logo.jpg";
                $request->file('logo')->move('logos', $filename);
                $crew_fields['logo_filename'] = '/logos/'.$filename;
            }
            // *** Add error handling for the file upload ***
        }

        // Save any changes to the Crew model
        $crew->update($crew_fields);
        // *** Add error handling/validation for the Crew model

        // Deal with the Helicopter fields:
        // For each Helicopter on the form, create new or update the existing Helicopter in the dB if necessary
        // (don't update the model if nothing has changed)

        foreach($helicopter_fields as $helicopter) {
            if(!empty($helicopter['tailnumber'])) {
                // Instantiate a new Helicopter - CONVERT TAILNUMBER TO ALL CAPS IN THE DATABASE
                $temp_heli = Helicopter::firstOrCreate(array('tailnumber' => strtoupper($helicopter['tailnumber'])));               

                $helicopter['crew_id'] = $id;

                $temp_heli->updateIfChanged($helicopter);
                // An error occurred during updateIfChanged()
                // Go back to the form and display errors
                // return redirect()->route('edit_crew', $crew->id)
                            //->withErrors($temp_heli->errors())
                //            ->withInput();
            }
        }

        // Everything completed successfully
        return redirect()->route('edit_crew', $crew->id)->with('alert', array('message' => 'Crew info saved!', 'type' => 'success'));
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!Auth::user()->isGlobalAdmin()) {
            // Only Global Admins can access this
            return redirect()->back()->withErrors("Unauthorized");
        }

        $crew = Crew::find($id);
        $crew_name = $crew->name;

        // Release all Helicopters from this crew (delete entries from the CrewsHelicopters table)
        // Delete all Users associated with this crew?
        $crew->delete();
        return redirect()->route('crews_index')->with('alert', array('message' => "'".$crew_name."' was deleted.", 'type' => 'success'));
    }
}
