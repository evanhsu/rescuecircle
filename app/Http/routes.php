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
// A "Status" is an object that belongs to 'Helicopter'.  
// A new Status is created when the Crew Status form is submitted.
// A Status cannot be deleted, only superceded. This maintains a history log of statuses.
Route::get('/helicopters/{tailnumber}/status',		array('as' => 'status_for_helicopter', 	'uses' => 'HelicopterController@status'));
// Route::post('/helicopters/{tailnumber}/status', 	array('as' => 'create_status', 			'uses' => 'StatusController@create' ));
Route::post('/helicopters/{tailnumber}/release', 	array('as' => 'release_helicopter', 	'uses' => 'HelicopterController@releaseFromCrew'));


// CREW IDENTITY
Route::get('/crews',                	array('as' => 'crews_index',    	'uses' => 'CrewController@index'));
Route::get('/crews/new',            	array('as' => 'new_crew',       	'uses' => 'CrewController@create'));
Route::post('/crews/new',           	array('as' => 'store_crew',     	'uses' => 'CrewController@store'));
Route::get('/crews/{id}',       		array('as' => 'crew',       		'uses' => 'CrewController@show'));
Route::post('/crews/{id}',				array('as' => 'update_crew',		'uses' => 'CrewController@update'));
Route::get('/crews/{id}/status',       	array('as' => 'status_for_crew',    'uses' => 'CrewController@status'));

Route::get('/crews/{id}/identity',  	array('as' => 'edit_crew',  		'uses' => 'CrewController@edit'));

Route::get('/crews/{id}/accounts',  	array('as' => 'users_for_crew',		'uses' => 'CrewController@accounts'));
Route::get( '/crews/{id}/accounts/new', array('as' => 'new_user_for_crew',  'uses' => 'Auth\AuthController@getRegister'));
Route::post('/crews/{id}/destroy',  	array('as' => 'destroy_crew',   	'uses' => 'CrewController@destroy'));


// Route::post('/accounts/reset',           /* UserController@postReset */);    // Accepts an email as POST parameter
// Route::get('/accounts/reset',            /* UserController@getReset */);     // Show password reset form


// SESSIONS
Route::get('/logout', array('as' => 'logout',   'uses' => 'Auth\AuthController@getLogout'));
Route::get('/login',  array('as' => 'login',    'uses' => 'Auth\AuthController@getLogin'));
Route::post('/login', array(                    'uses' => 'Auth\AuthController@postLogin'));


// GLOBAL ADMIN ACCOUNT MANAGEMENT
Route::get('/accounts',					array('as' => 'users_index',	'uses' => 'Auth\AuthController@index'));
Route::post('/accounts/new',			array('as' => 'register_user',	'uses' => 'Auth\AuthController@postRegister'));
Route::get('/accounts/{id}',			array('as' => 'edit_user',		'uses' => 'Auth\AuthController@edit'));
Route::post('/accounts/{id}',			array('as' => 'update_user',	'uses' => 'Auth\AuthController@update'));
Route::post('/accounts/{id}/destroy',	array('as' => 'destroy_user',	'uses' => 'Auth\AuthController@destroy'));


//Route::get('/404', array('as' => 'not_found', 'uses' => 'ErrorController@notFound'));
