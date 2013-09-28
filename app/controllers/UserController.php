<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::all();
 			
 		if($users) {
			return Response::json(
				array(
					'error' => false,
					'users' => $users->toArray()
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'error' 	=> true,
					'message' 	=> 'There is no user yet'
				),
				404
			);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

		$user = User::where('id','=', $id)->first();

		if($user) {
			return Response::json(
				array(
					'error' => false,
					'user' => $user->toArray()
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'error' 	=> true,
					'message' 	=> 'Book not found'
				),
				404
			);
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = User::where('id', Auth::user()->id)->find($id);

		if($user) {
			$user->delete();

			return Response::json(
				array(
					'error' 	=> false,
					'message'	 => 'User deleted'
				),
				200
			);

			/*
				TODO logout the user
			*/

		}
		else {
			return Response::json(
				array(
					'error' 	=> true,
					'message' 	=> 'User not deleted'
				),
				404
			);
		}
		
	}

}