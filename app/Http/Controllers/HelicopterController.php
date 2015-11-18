<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helicopter;


class HelicopterController extends Controller
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
        //
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

    public function releaseFromCrew($tailnumber) {
        // Disassociate the specified helicopter with this Crew (set crew_id = null) if the current user has authorization

        $heli = Helicopter::where('tailnumber',$tailnumber)->first();

        if(is_null($tailnumber)) {
            // Helicopter not found. Nothing to release.
            abort(404, "Helicopter not found");
        }
        else {
            // Make sure the current user is authorized to release this helicopter
            $user = Auth::user();
            if($user->isAdminForCrew($heli->crew_id)) {
                if($heli->release()) return response()->json(['status' => 'success']);
                else abort(500); //Something prevented the helicopter from being released
            }
            else {
                // Unauthorized
                abort(401, "Unauthorized");
            }
        }

    }
}

