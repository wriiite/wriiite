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

// Route::filter('api_checkauth', function()
// {
//   //user id
//   $user_id = (int) Input::get('user_id');

//   //signature
//   $sig = Input::get('sig');

//   try {
//     //Lookup user
//     $user = Sentry::user($user_id);

//     if($user) {
//       //user email
//       $email = $user->email;
//       //user api key
//       $api_key = $user->api_key;
//       //recreate signature
//       $_sig = hash_hmac("sha256",$email.$user_id,$api_key);
//       if($_sig === $sig) {
//           return Response::json(array("message"=>"Request Ok"),200);
//       }
//       else {
//           return Response::json(array("message"=>"Request Bad"),400);
//       }
//     }
//     else {
//       return Response::json(array("message"=>"Request not authorized"),401);
//     }
//   }
//   catch (Sentry\SentryException $e) {
//     $errors = $e->getMessage(); // catch errors such as user not existing or bad fields
//     return Response::json(array("message"=>$errors),404);
//   }

// });

Route::get('/', function() {
	return View::make('hello');
});

Route::get('/authtest', array('before' => 'auth.basic', function() {
	return View::make('hello');
}));


// Route group for API
header('Access-Control-Allow-Origin: *');
Route::group(array('prefix' => 'api/v1'), function() {
	Route::get('/',function() {
		return Response::json(["message"=>"API v0.2 is alive"]);
	});

	Route::resource('books', 'BookController');
	Route::get('users/{id}/books', 'BookController@ownedByUser');

	Route::resource('users', 'UserController');
	Route::get('books/{id}/users', 'UserController@bookContributors');

	Route::resource('pages', 'PageController');
	Route::get('users/{id}/pages', 'PageController@ownedByUser');

	Route::post('auth/login', 'AuthController@login');
	Route::get('auth/check', 'AuthController@check');
	Route::get('auth/logout', 'AuthController@logout');
  	Route::resource('auth', 'AuthController');

});