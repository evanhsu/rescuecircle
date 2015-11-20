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
                        ]]);

        // Require the current user to have certain permission before allowing access (in addition to being logged in)
        $this->middleware('hasPermission:crew_admin,true', ['only' => [ 'create',
                                                                        'getRegister',
                                                                        ]]);
        
        $this->middleware('hasPermission:global_admin', ['only' => ['index']]);
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
        // Run the form input through the validator
        // This validator replaces the functionality of the 'HasPermission' middleware specifically for registering new user account.
        // That's why the middleware is NOT set to run during requests to this controller action.
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
                // If this user is NOT an Admin, land on the status update page for their crew
                return redirect()->route('status_for_crew', [$user->crew_id]);
            }
        } else {
            // Authentication failed
            $errors = new MessageBag(['password' => ['Those credentials are invalid.']]);

            return redirect('/login')
                ->withErrors($errors)
                ->withInput($request->only('email')); // Send back input to autopopulate fields;
        }
    } // End postLogin()

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
        $request->session()->flash('active_menubutton','accounts'); // Tell the menubar which button to highlight
        return view('auth.new_user')->with("crew_id",$id);
    }


    public function index(Request $request) {

        $users = User::orderBy('firstname', 'asc')
                ->orderBy('lastname','asc')
                ->get();
        $crews = Crew::orderBy('name')->get();

        $request->session()->flash('active_menubutton','accounts'); // Tell the menubar which button to highlight
        return view('auth.index', ['users' => $users,
                                   'crews' => $crews ]);

    } // End index()


    public function edit($id) {
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

        $alert_message = array('message' => "That account was deleted.", 'type' => 'success');
        if(Auth::user()->isGlobalAdmin()) {
            return redirect()->route('users_index')->with('alert', $alert_message);
        }
        else {
            return redirect()->route('users_for_crew',Auth::user()->crew_id)->with('alert', $alert_message);
        }

    } // End destroy()
}
