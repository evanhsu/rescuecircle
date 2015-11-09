<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', array('uses' => 'MapController@getMap'));
Route::get('/feed.json', array('uses' => 'MapController@getMapJSON'));


// HOME
// Route::get('/', /* MapController@show */);


// CREW STATUS
// A "Status" is an object that belongs to 'Crew'.  
// A new Status is created when the Crew Status form is submitted.
// A Status cannot be deleted, only superceded. This maintains a history log of statuses.
Route::get('/crews/{id}/status', array('uses' => 'CrewController@status'));
// Route::post('/crews/:id/status', /* StatusController@create */);


// CREW IDENTITY
// Route::get('/crews',      /* CrewController@index */);
// Route::get('/crews/:id',  /* CrewController@show */);
// Route::post('/crews/:id', /* CrewController@update */);


// CREWMEMBER ACCOUNTS
// Route::get('/crews/:id/accounts',        /* CrewController@accounts */);
// Route::get('/crews/:id/accounts/new',    /* UserController@new */);
// Route::get('/accounts/:id',              /* UserController@show */);
// Route::post('/accounts/:id',             /* UserController@update */);
// Route::post('/accounts/:id/destroy',     /* UserController@destroy */);
// Route::post('/accounts/reset',           /* UserController@postReset */);    // Accepts an email as POST parameter
// Route::get('/accounts/reset',            /* UserController@getReset */);     // Show password reset form



// SESSIONS
Route::get('/logout', array('uses' => 'Auth\AuthController@getLogout'));
Route::get('/login',  array('uses' => 'Auth\AuthController@getLogin'));
Route::post('/login', array('uses' => 'Auth\AuthController@postLogin'));


// GLOBAL ADMIN ACCOUNT MANAGEMENT
// Route::post('/admin/crews/:id/destroy', /* AdminCrewController@destroy */);
// Route::get('/admin/crews/new',          /* AdminCrewController@new */ );
// Route::post('/admin/crews/create',      /* AdminCrewController@create */);
Route::get('/crews', array('uses' => 'CrewController@getCrews'));

// Route::get('/accounts',    /* UserController@index */ );


