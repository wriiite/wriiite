<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function() {
	return View::make('hello');
});

Route::get('/authtest', array('before' => 'auth.basic', function() {
	return View::make('hello');
}));


// Route group for API
Route::group(array('prefix' => 'api/v1', 'before' => 'auth.basic'), function() {

	// should be set somewhere else
	header('Access-Control-Allow-Headers: X-Requested-With, X-HTTP-Method-Override, Content-Type, Accep');
	header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
	header('Access-Control-Allow-Origin: *');

	Route::get('/',function() {
		return Response::json(["message"=>"API v1 is alive"]);
	});

	Route::resource('book', 'BookController');
	Route::get('user/{id}/book', 'BookController@ownedByUser');

	Route::resource('user', 'UserController');
	Route::get('book/{id}/user', 'UserController@bookContributors');

	Route::resource('page', 'PageController');
	Route::get('user/{id}/page', 'PageController@ownedByUser');
});