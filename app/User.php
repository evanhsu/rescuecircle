<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Crew;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['firstname', 'lastname', 'email', 'password', 'crew_id'];

    /**
     * The attributes excluded from the models JSON form.
     *
     * @var array
     */
    protected $hidden = ['encrypted_password', 'salt', 'remember_token'];

    /**
     * Define relationships to other Eloquent models
     *
     */
    public function crew() {
        return $this->hasOne(Crew::class);
    }


    protected function authenticated(Request $request, User $user)
    {
        // This is the URL that a user is sent to after successfully logging in
        return redirect()->intended('/crews/'.$this->crew_id.'/status');
    }
    
    public function getAuthPassword() {
        // This function is used by the Auth::attempt function to retrieve the custom-named password field
        return $this->encrypted_password;
    }

    /*
    *   Determine whether this User can alter user accounts for members of the specified crew
    *
    *   @var boolean
    */
    public function isAdminForCrew($crew_id) {
        if( (!empty($crew_id) && ($this->crew_id == $crew_id)) || $this->isGlobalAdmin()) {
            return true;
        }
        else {
            return false;
        }

        // This could be changed to use the hasPermission method
    }

    public function isGlobalAdmin() {
        if($this->global_admin == 1) {
            return true;
        }
        else {
            return false;
        }

        // This could be changed to use the hasPermission method
    }

    public function hasPermission($permission) {
        // Determine whether this user has the specified $permission in their User.permission array
        //
        // INPUT:
        //  string $permission
        //
        // OUTPUT:
        //  bool TRUE|FALSE

        //return in_array($permission, $this->permissions);
        return true; //**************************************** Change this once the User->permissions array has been implemented ****
    }
}
?>