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

// HOME
Route::get('/', array('as' => 'map', 'uses' => 'MapController@getMap'));
Route::get('/feed.json', array('uses' => 'MapController@getMapJSON'));


// CREW STATUS
// A "Status" is an object that belongs to 'Crew'.  
// A new Status is created when the Crew Status form is submitted.
// A Status cannot be deleted, only superceded. This maintains a history log of statuses.
Route::get('/crews/{id}/status', array('as' => 'status_for_crew', 'uses' => 'CrewController@status'));
// Route::post('/crews/:id/status', /* StatusController@create */);

Route::get('/crews/new',            array('as' => 'new_crew',       'uses' => 'CrewController@create'));
Route::post('/crews/new',           array('as' => 'store_crew',     'uses' => 'CrewController@store'));

// CREW IDENTITY
Route::get('/crews/{id}',       	array('as' => 'crew',       'uses' => 'CrewController@show'));
Route::get('/crews/{id}/identity',  array('as' => 'edit_crew',  'uses' => 'CrewController@edit'));
Route::post('/crews/{id}',			array('as' => 'update_crew','uses' => 'CrewController@update'));


// CREWMEMBER ACCOUNTS
// Route::get('/crews/:id/accounts',        /* CrewController@accounts */);
// Route::get('/crews/:id/accounts/new',    /* UserController@new */);
// Route::get('/accounts/:id',              /* UserController@show */);
// Route::post('/accounts/:id',             /* UserController@update */);
// Route::post('/accounts/:id/destroy',     /* UserController@destroy */);
// Route::post('/accounts/reset',           /* UserController@postReset */);    // Accepts an email as POST parameter
// Route::get('/accounts/reset',            /* UserController@getReset */);     // Show password reset form



// SESSIONS
Route::get('/logout', array('as' => 'logout',   'uses' => 'Auth\AuthController@getLogout'));
Route::get('/login',  array('as' => 'login',    'uses' => 'Auth\AuthController@getLogin'));
Route::post('/login', array(                    'uses' => 'Auth\AuthController@postLogin'));


// GLOBAL ADMIN ACCOUNT MANAGEMENT
Route::get('/crews',                array('as' => 'crews_index',    'uses' => 'CrewController@index'));



Route::post('/crews/{id}/destroy',  array('as' => 'destroy_crew',   'uses' => 'CrewController@destroy'));

// Route::get('/accounts',    /* UserController@index */ );

//Route::get('/404', array('as' => 'not_found', 'uses' => 'ErrorController@notFound'));
