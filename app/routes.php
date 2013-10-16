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

Route::group(array('prefix' => 'api/v1'), function() {
	Route::get('/',function() {
		return Response::json(["message"=>"API v1 is alive"]);
	});

	Route::resource('books', 'BookController');
	Route::get('users/{id}/books', 'BookController@ownedByUser');

	Route::resource('users', 'UserController');
	Route::get('books/{id}/users', 'UserController@bookContributors');

	Route::resource('pages', 'PageController');
	Route::get('users/{id}/pages', 'PageController@ownedByUser');
});