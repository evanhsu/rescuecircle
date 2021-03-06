<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Crew;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
// use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;  // Using custom registration logic instead
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;

Class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users.
    |
    */

    use AuthenticatesUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // RedirectIfAuthenticated (users who are already logged in will be redirected before this controller takes action)
        $this->middleware('guest', ['only' => [ 'getLogin',
                                                'postLogin',
                        ]]);

        // Require a user to be logged in before accessing these actions:
        $this->middleware('auth', ['except' => ['getLogin',
                                                'postLogin',
                                                'getEmail', // The password reset form
                        ]]);
    }

    //Users who fail authentication on the login form will be redirected here.
    protected $loginPath = '/login';

    // Users who authenticate successfully will be redirected here:
    protected $redirectTo = '/';


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $v = Validator::make($data, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

        // Confirm that the current user is a member of the same crew as the account being created
        // The 'crew_id' form field is auto-populated and hidden, but could be tampered with.
        // (Guests cannot create an account, only a logged-in user can create a new user account)
        // Global Admins will always be allowed through this validation step by the 'isAdminForCrew()' function.
        $v->after(function($v) use ($data) {
            if(Auth::user()->isAdminForCrew($data['crew_id']) === false) {
                $v->errors()->add('crew_id',"You can only create accounts for your own crew");
            }
        });
        return $v;
    }

    /**
     * Handle a New User request for the application.
     * This Action accepts input from the "New User" form and passes it
     * through the Validator.
     * If validation passes, THEN the form input is passed to the CREATE action.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        // Make sure this user is authorized...
        if(Auth::user()->cannot('actAsAdminForCrew', $request->get('crew_id'))) {
            // The current user does not have permission to create a user account for this crew
            return redirect()->back()->withErrors("You're not authorized to register users for that crew!");
        }
        // Authorization complete - continue...
        // Run the form input through the validator
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
           /* $this->throwValidationException(
                $request, $validator
            );*/
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // Create and Store the new user
        // ****** Generate a random password for the new User
        // $new_user_data['password'] = generatePassword();
        // $new_user = $this->create($new_user_data);
        //
        // Send WELCOME email to new user, including the randomly-generated password
        // sendEmail($new_user, $new_user_data['password']);


        $this->create($request->all());
        

        if(Auth::user()->isGlobalAdmin()) {
            return redirect()->route('users_index');
        } else {
            return redirect()->route('users_for_crew', ["id" => Auth::user()->crew_id]);
        }
    }

    /**
     * Create a new user instance AFTER a valid registration.
     * This action is protected and is called from the 'postRegister' action.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        // ****** Change this to generate a random password and send it to the user in an email. ***********

        return User::create([
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'email'     => $data['email'],
            'encrypted_password' => Hash::make($data['password']),
            'crew_id'   => $data['crew_id'],
        ]);
    }




    public function getLogin(Request $request) {

        // Display the login form
        // If the user is already logged in, this request will be intercepted by the Auth middleware (/app/Http/middleware/RedirectIfAuthenticated.php)
        $request->session()->flash('active_menubutton','login'); // Tell the menubar which button to highlight
        return view('login');
    } // End getLogin()

    public function postLogin(Request $request) {

        // Validation has passed, now perform authentication
        $login_credentials = array(
            'email'     => $request->input('email'),
            'password'  => $request->input('password'));

        if(Auth::attempt($login_credentials)) {
            // Authentication passed, user is logged in
            $user = Auth::user();
            if($user->isGlobalAdmin()) {
                // If this user is an Admin, land on the list of all Crews (Crews@getIndex)
                return redirect()->route('crews_index');
            }
            else {
                // If this user is NOT an Admin, decide which page to display:
                return $this->redirectToLandingPage();
            }
        } else {
            // Authentication failed
            $errors = new MessageBag(['password' => ['Those credentials are invalid.']]);

            return redirect('/login')
                ->withErrors($errors)
                ->withInput($request->only('email')); // Send back input to autopopulate fields;
        }
    } // End postLogin()

    protected function redirectToLandingPage() {
        // Determine what page the current user should land on after a successful login.
        // Return a RedirectResponse to that page.
        $user = Auth::user();
        // var_dump($user);

        if($user->isGlobalAdmin()) {
            // If this user is an Admin, land on the list of all Crews (Crews@getIndex)
            return redirect()->route('crews_index');
        }
        else {
            // If this user is NOT an Admin, decide which page to display:
            //   1. Look for the most recent Status that this user has submitted
            //   2. If found, go to the Status Update page for the Crew or Aircraft that this User last updated.
            //   3. If the Crew is statusable, go to the New Status form for the crew.
            //   4. If the Crew has aircrafts that are statusable, go to the New Status form for the Aircraft with highest alphabetical priority.
            //   5. If not found, or this User no longer has permission, go to this User's Crew Identity page.

            // Step 1
            $last_status_from_user = $user->lastStatus();
            //echo "<br /><br />\n\nLast Status: ".$last_status_from_user->id."\n\n<br /><br />\n\n";

            // Step 2
            if(!is_null($last_status_from_user)) {
                $route_params = $last_status_from_user->redirectToNewStatus();

                // Make sure this user is authorized to access this Aircraft or Crew...
                if(Auth::user()->can('actAsAdminForCrew', $last_status_from_user->crewToUpdate())) {
                    // This User is authorized to access the same resource that was updated last time
                    return redirect()->route($route_params['route_name'], $route_params['id']);
                }
                else {
                    // This aircraft/crew has changed owndership and this user can no longer access it
                    // Redirect to the Crew Identity page
                    return redirect()->route('edit_crew',$user->crew_id);
                }
            }

            // Step 3
            elseif($user->crew->is_not_an_aircraft_crew()) {
                return redirect()->route('new_status_for_crew', $user->crew_id);
            }

            // Step 4|5
            elseif($user->crew->is_an_aircraft_crew()) { 
                // Look for the first Aircraft owned by this Crew
                $aircraft = $user->crew->aircrafts()->orderBy('tailnumber')->first();
                if(is_null($aircraft)) {
                    // Step 5 (This crew is supposed to have aircrafts, but none were found)
                    return redirect()->route('edit_crew',$user->crew_id);
                }
                else {
                    // Step 4 (This crew has at least one aircraft)
                    return redirect()->route('new_status_for_aircraft',$aircraft->tailnumber);
                }
            }

            else {
                // The $user is not a GlobalAdmin, nor does he belong to a Crew (this shouldn't happen).
                // Delete the user and display a message.
                // (If the User is not deleted, a new account using the same email cannot be created and a Global Admin
                //  will need to intervene to change the Crew for an existing User).

                $user->delete();
                return redirect()->back()->withErrors("Your crew has been removed from the system. Contact an admin for support.");
            }
        }

    }

    public function getLogout(Request $request) {
        if(Auth::check()) {
            Auth::logout();

            // Redirect to the home page with a message
            return redirect('/')->with('alert',array('message' => 'You have been logged out.', 'type' => 'success'));
        }
        else {
            // User wasn't logged in, just redirect to home
            return redirect('/');
        }
    } // End getLogout()


    /**
     * Show the "Create New User" form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister(Request $request, $id)
    {
        $crew = Crew::findOrFail($id);
        if(Auth::user()->cannot('actAsAdminForCrew', $crew)) {
            // The current user does not have permission to register a user for the specified crew
            return redirect()->back()->withErrors("You're not authorized to register users for that crew!");
        }

        // Authorization complete - continue...
        $request->session()->flash('active_menubutton','accounts'); // Tell the menubar which button to highlight
        return view('auth.new_user')->with("crew_id",$id);
    }


    public function index(Request $request) {

        if(!Auth::user()->isGlobalAdmin()) {
            // Only Global Admins can access this
            return redirect()->back()->withErrors("Unauthorized");
        }

        // Authorization complete - continue...
        $users = User::orderBy('firstname', 'asc')
                ->orderBy('lastname','asc')
                ->get();
        $crews = Crew::orderBy('name')->get();

        $request->session()->flash('active_menubutton','accounts'); // Tell the menubar which button to highlight
        return view('auth.index', ['users' => $users,
                                   'crews' => $crews ]);

    } // End index()


    public function edit($id) {

        $target_user = User::findOrFail($id);

        // Make sure this user is authorized...
        if(Auth::user()->cannot('actAsAdminForCrew', $target_user->crew_id)) {
            // The current user does not have permission to perform admin functions for this crew
            return redirect()->back()->withErrors("You're not authorized to access that crew!");
        }

        // Authorization complete - continue...
        return 'Edit account: '.$id;
    } // End edit()


    public function destroy($id) {
        // Delete the User with ID $id
        $user_to_destroy = User::findOrFail($id);

        if(Auth::user()->cannot('destroy_user', $user_to_destroy)) {
            // The current user does not have permission to destroy the requested user
            return redirect()->back()->withErrors("You're not authorized to destroy that Account!");
        }

        // Authorization complete - continue...
        $user_to_destroy->delete();

        $alert_message = array('message' => "That account was deleted.", 'type' => "success");
        if(Auth::user()->isGlobalAdmin()) {
            return redirect()->route('users_index')->with('alert', $alert_message);
        }
        else {
            return redirect()->route('users_for_crew',Auth::user()->crew_id)->with('alert', $alert_message);
        }

    } // End destroy()
}
