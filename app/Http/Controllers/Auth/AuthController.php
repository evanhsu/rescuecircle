<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
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
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // RedirectIfAuthenticated (users who are already logged in will be redirected before this controller takes action)
        $this->middleware('guest', ['except' => [   'getLogout',
                                                    'index'
                        ]]);

        // Require the current user to have certain permission before allowing access
        $this->middleware('hasPermission:crew_admin,true', ['only' => [ 'destroy',
                                                                        'create',
                                                                        'store']]);
        
        $this->middleware('hasPermission:global_admin', ['only' => ['index']]);
    }

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
            'password' => 'required|confirmed|min:6',
        ]);

        // Confirm that the current user is a member of the same crew as the account being created
        // The 'crew_id' form field is auto-populated and hidden, but could be tampered with.
        // (Guests cannot create an account, only a logged-in user can create a new user account)
        $v->after(function($v) {
            if(Auth::user()->isAdminForCrew($data['crew_id']) == false) {
                $v->errors()->add('crew_id',"You can only create accounts for your own crew");
            }
        });
        return $v;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'encrypted_password' => Hash::make($data['password']),
        ]);
    }

    //Users who fail authentication on the login form will be redirected here.
    protected $loginPath = '/login';

    // Users who authenticate successfully will be redirected here:
    protected $redirectTo = '/';


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

    public function index(Request $request) {

        $users = User::orderBy('firstname', 'asc')
                ->orderBy('lastname','asc')
                ->get();

        return view('auth.index', ['users' => $users]);

    } // End index()

    public function store(Request $request) {


    } // End store()

    public function destroy($id) {
        // Delete the User with ID $id


    } // End destroy()
}
