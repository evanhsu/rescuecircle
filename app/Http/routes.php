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
Route::get('/proxy', array('as' => 'proxy', 'uses' => 'ProxyController@proxy'));

// STATUS
// A "Status" is an object that belongs to 'Helicopter' or 'Crew'.  
// A new Status is created when a "Status Update" form is submitted.
// A Status cannot be deleted or edited, only superceded. This maintains a history log of statuses.
//Route::get('/status/{id}',	array('as' => 'show_status', 		'uses' => 'StatusController@show' ));
Route::post('/status',      array('as' => 'create_status',      'uses' => 'StatusController@store' ));
Route::get('/status/all', 	array('as' => 'current_statuses',	'uses' => 'StatusController@currentForAllResources' ));

// DEBUGGING ONLY
if(env("APP_DEBUG")) {
	// These routes are only enabled for debugging to test internal functionality
	Route::get('/test/token',		array('as' => 'test_token',			'uses' => 'TestController@testtoken')); // This will tell you if the application token is working with the ArcGIS server
	Route::get('/test/findfeature',	array('as' => 'test_find_feature',	'uses' => 'TestController@findFeature')); 
	Route::get('/test/deletefeature/{id}',array('as'=> 'test_delete_feature','uses' => 'TestController@deleteFeature')); 
	Route::get('/proxyverify', 		array('as' => 'proxy', 				'uses' => 'ProxyController@proxyVerify')); // Test that the environment will support the ESRI proxy script
}

// AIRCRAFT
Route::get('/aircraft',							array('as' => 'aircraft_index', 				'uses' => 'AircraftController@index'));
Route::get('/aircraft/{tailnumber}/status',		array('as' => 'current_status_for_aircraft', 	'uses' => 'AircraftController@showCurrentStatus'));
Route::get('/aircraft/{tailnumber}/update',		array('as' => 'new_status_for_aircraft', 		'uses' => 'AircraftController@newStatus'));
Route::post('/aircraft/{tailnumber}/release', 	array('as' => 'release_aircraft', 				'uses' => 'AircraftController@releaseFromCrew'));
Route::post('/aircraft/{tailnumber}/destroy',  	array('as' => 'destroy_aircraft',   			'uses' => 'AircraftController@destroy'));


// CREWS
Route::get('/crews',                	array('as' => 'crews_index',    				'uses' => 'CrewController@index'));
Route::get('/crews/new',            	array('as' => 'new_crew',       				'uses' => 'CrewController@create'));
Route::post('/crews/new',           	array('as' => 'store_crew',     				'uses' => 'CrewController@store'));
Route::get('/crews/{id}',       		array('as' => 'crew',       					'uses' => 'CrewController@show'));
Route::post('/crews/{id}',				array('as' => 'update_crew',					'uses' => 'CrewController@update'));
Route::get('/crews/{id}/status',       	array('as' => 'current_status_for_crew',		'uses' => 'CrewController@showCurrentStatus'));
Route::get('/crews/{id}/update',       	array('as' => 'new_status_for_crew',    		'uses' => 'CrewController@newStatus'));
Route::get('/crews/{id}/post',       	array('as' => 'status_form_selector_for_crew',  'uses' => 'CrewController@redirectToStatusUpdate'));
Route::get('/crews/{id}/identity',  	array('as' => 'edit_crew',  					'uses' => 'CrewController@edit'));
Route::get('/crews/{id}/accounts',  	array('as' => 'users_for_crew',					'uses' => 'CrewController@accounts'));
Route::get( '/crews/{id}/accounts/new', array('as' => 'new_user_for_crew',  			'uses' => 'Auth\AuthController@getRegister'));
Route::post('/crews/{id}/destroy',  	array('as' => 'destroy_crew',   				'uses' => 'CrewController@destroy'));


// ACCOUNTS
Route::get('/accounts',					array('as' => 'users_index',	'uses' => 'Auth\AuthController@index'));
Route::post('/accounts/new',			array('as' => 'register_user',	'uses' => 'Auth\AuthController@postRegister'));
Route::get('/accounts/{id}',			array('as' => 'edit_user',		'uses' => 'Auth\AuthController@edit'));
Route::post('/accounts/{id}',			array('as' => 'update_user',	'uses' => 'Auth\AuthController@update'));
Route::post('/accounts/{id}/destroy',	array('as' => 'destroy_user',	'uses' => 'Auth\AuthController@destroy'));


// PASSWORD RESET
// Password reset link request routes...
Route::get('resetpassword', 			array('as' => 'password_link_request_form', 	'uses' => 'Auth\PasswordController@getEmail'));
Route::post('resetpassword', 			array('as' => 'process_password_link_request',	'uses' => 'Auth\PasswordController@postEmail'));
// Password reset routes...
Route::get('password/reset/{token}', 	array('as' => 'password_reset_form', 'uses' => 'Auth\PasswordController@getReset'));
Route::post('password/reset', 			array('as' => 'reset_password', 'uses' => 'Auth\PasswordController@postReset'));


// SESSIONS
Route::get('/logout', array('as' => 'logout',   'uses' => 'Auth\AuthController@getLogout'));
Route::get('/login',  array('as' => 'login',    'uses' => 'Auth\AuthController@getLogin'));
Route::post('/login', array(                    'uses' => 'Auth\AuthController@postLogin'));


// ERRORS
//Route::get('/404', array('as' => 'not_found', 'uses' => 'ErrorController@notFound'));
