<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class HasPermission
{
    /**
     * Handle an incoming request.
     * This Middleware checks the current user's Permissions array for $permission.
     * If this user has the specified $permission, let them through.
     * Otherwise, redirect to the main page.
     *
     * The optional 2nd argument determines whether or not to require that this user
     * has the same crew_id as the page being requested.
     * i.e. When $enforce_same_crew is TRUE, a user with crew_id=2 cannot access /crews/1/status
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure $next
     * @param  String   $permission
     * @param  String   $enforce_same_crew
     * @return mixed
     */
    public function handle($request, Closure $next, $permission, $enforce_same_crew = false)
    {
        // Convert $enforce_same_crew from a string to a boolean
        // The case-insensitive string "true" is converted to boolean TRUE.
        // Any other string value is converted to boolean FALSE.
        $enforce_same_crew = ($enforce_same_crew === strtolower('true'));


        if(!$request->user()->hasPermission($permission)) {
            return redirect()->route('map');
        }
        elseif($enforce_same_crew && (Route::current()->getParameter('id') != $request->user()->crew_id)) {
            return redirect()->route('map');
        }

        // $request->session()->flash('perms',$permission); // Debugging output
        return $next($request);
    }
}
