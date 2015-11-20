<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);


        // The current user must be on the same crew as the user being destroyed, unless the current user is a Global Admin
        $gate->define('destroy_user', function($current_user, $user_to_destroy) {

            return $current_user->crew_id === $user_to_destroy->crew_id;

        })->before(function($current_user, $ability) {
            // Global Admin users will always be granted this permission
            if($current_user->isGlobalAdmin()) {
                return true;
            }
        });



        // The current user must be on the same crew as the Crew object passed in AND have the specified User->permission
        // If $action is null, User->hasPermission($action) will return TRUE
        $gate->define('performActionForCrew', function($current_user, $target_crew, $action=null) {

            return ($current_user->crew_id === $target_crew->id)
                    && ($current_user.hasPermission($action));

        })->before(function($current_user, $ability) {
            // Global Admin users will always be granted this permission
            if($current_user->isGlobalAdmin()) {
                return true;
            }
        });


        // The current user must be a Crew Admin for the target Crew
        $gate->define('actAsAdminForCrew', function($current_user, $target_crew) {

            // Allow $target_crew to be passed in as either a Crew object OR an Integer crew_id
            // If $target_crew is NULL, return FALSE.... UNLESS the $current_user is a Global Admin
            if(is_object($target_crew)) return $current_user->isAdminForCrew($target_crew->id);
            elseif(is_numeric($target_crew)) return $current_user->isAdminForCrew(intval($target_crew));
            else return false; // An invalid data type was passed in for $target_crew (only integer or Crew Object are allowed)

        })->before(function($current_user, $ability) {
            // Global Admin users will always be granted this permission
            if($current_user->isGlobalAdmin()) {
                return true;
            }
        });
    }
}
