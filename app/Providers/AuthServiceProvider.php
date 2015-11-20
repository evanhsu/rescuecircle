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


        $gate->define('destroy_user', function($current_user, $user_to_destroy) {
            // The current user must be on the same crew as the user being destroyed, unless the current user is a Global Admin
            return $current_user->crew_id === $user_to_destroy->crew_id;
        })->before(function($current_user, $ability) {
            if($current_user->isGlobalAdmin()) {
                return true;
            }
        });

        //
    }
}
