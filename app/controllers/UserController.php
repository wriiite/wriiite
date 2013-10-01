<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$page 			= Input::get('page',1);
		$item_perPage 	= 2;
		$users 			= User::orderBy('created_at', 'desc')->forPage($page,$item_perPage)->get();
			
		if($users) {
			return Response::json(
				array(
					'error' => false,
					'users' => $users->toArray(),
					'page'  => $page 
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'error' 	=> true,
					'message' 	=> 'There\'s no users here'
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
	public function bookContributors($id)
	{
		$book = Book::where('id',$id)->first();

		if($book) {

			if(Input::get('sort') && Input::get('sort') == 'valid') {
				$pages 		= Page::where('book_id', $book->id)->where('status',1)->get();
			}
			else {
				$pages 		= Page::where('book_id', $book->id)->get();
			}

			$users_id 	= [];

			foreach($pages as $p)
			{
				$users_id[] = $p->user_id;
			}

			$users = User::whereIn('id', $users_id)->get();
			
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
					'message' 	=> 'This book doesn\'t exist'
				),
				404
			);
		}
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
					'message' 	=> 'User not found'
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