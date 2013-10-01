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
	Route::get('/',function() {
		return Response::json(["message"=>"API v1 is alive"]);
	});

	Route::resource('book', 'BookController');

	Route::resource('user', 'UserController');
	Route::get('user/{id}/book', 'BookController@ownedByUser');
	Route::get('user/{id}/page', 'PageController@byuser');

	Route::resource('page', 'PageController');
});