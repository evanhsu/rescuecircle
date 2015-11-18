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

class CrewController extends Controller
{
    public function __construct()
    {
        // Use the 'auth' middleware to require users to log in
        // This applies to ALL actions within this controller
        $this->middleware('auth');

        // Require the current user to have certain permission before allowing access
        $this->middleware('hasPermission:crew_admin,true', ['only' => [ 'status',
                                                                        'show',
                                                                        'edit',
                                                                        'update']]);
        $this->middleware('hasPermission:global_admin', ['only' => ['index',
                                                                    'create',
                                                                    'store',
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
        $crews = Crew::orderBy('name', 'asc')->get();
        return view('crews.index', ['crews' => $crews]);
        // return "Index of all crews";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                $temp_heli = Helicopter::firstOrCreate(array('tailnumber' => $helicopter['tailnumber']));               

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
        $crew = Crew::find($id);
        $crew_name = $crew->name;

        // Release all Helicopters from this crew (delete entries from the CrewsHelicopters table)
        // Delete all Users associated with this crew?
        $crew->delete();
        return redirect()->route('crews_index')->with('alert', array('message' => "'".$crew_name."' was deleted.", 'type' => 'success'));
    }
}
