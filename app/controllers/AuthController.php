<?php

class AuthController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		try {
			$user = array();
			if (Auth::check()) {
				$user = Auth::user();
				if ($user) {
					$user->password = null;
					$user->api_key 	= null;
					$user = $user->toArray();
				}
			}
 
 
			$data = array(
				'user' => $user
			);
 
			return Response::json($data, 200);
		} catch(Exception $e) {
			return $e->getMessage();
		}
	}

	public function logout()
	{
		try {
			return Auth::logout();
		} catch(Exception $e) {
			return $e->getMessage();
		}
	}



	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		try {
			$email 		= Input::get('email');
			$password 	= Input::get('password');
			$username 	= Input::get('username');
 
			$v = Validator::make(array(
				'email' 	=> $email,
				'username' 	=> $username,
				'password' 	=> $password
				
			), array(
				'email' 	=> 'email|required|unique:users',
				'username' 	=> 'required',
				'password' 	=> 'required|min:6'
				
			));
 
			if ($v->fails()) {
				$messages = $v->messages();
 
				throw new Exception($messages);
			}
 			
			$user 				= new User;
			$user->email 		= $email;
			$user->username 	= $username;
			$user->password 	= Hash::make($password);
 			$user->api_key 		= Hash::make($username);
			$user->save();

			Auth::login($user, 1);
 
			$data = array(
				'success' => true,
			);
 
			return Response::json($data, 200);
		} catch(Exception $e) {
			$data = array(
				'error' => $e->getMessage()
			);
 
			return Response::json($data, 404);
		}
	}

	public function login()
	{
		try {

			$email 		= Input::get('email');
			$password	= Input::get('password');

			$s = array(
				'email'		=> $email,
				'password'	=> $password
				);
			if (! Auth::attempt($s)) {
				throw new Exception("Incorrect email or password.");
			}
 
			$data = array(
				'success' => ''
			);
 
			return Response::json($data, 200);
		} catch(Exception $e) {
			Auth::logout();
 
			$data = array(
				'error' => $e->getMessage()
			);
 
			return Response::json($data, 404);
		}
	}

	public function check()
	{
		try {
			$data = array(
				'check' => Auth::check(),
			);
 
			return Response::json($data, 200);
		} catch(Exception $e) {
			Report::log($e->getMessage());
		}
	}

}