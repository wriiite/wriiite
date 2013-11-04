<?php

class UserController extends \BaseController {

	public function __construct() {
		$this->beforeFilter('auth.basic', array('except' => array('index', 'show', 'store','bookContributors')));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$max 	= intval(Input::get('max',10));
		$offset = intval(Input::get('offset',0));
		$users 			= User::orderBy('created_at', 'desc')->take($max)->skip($offset)->get();

		if($users) {
			return Response::json(
				array(
					'items' 	=> $users->toArray(),
					'metadata'	=> array(
						'max'		=> $max,
						'offset'	=> $offset,
						'error'		=> false
					)
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'metadata'	=> array(
						'error' 	=> true
					)
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
		$max 	= intval(Input::get('max',10));
		$offset = intval(Input::get('offset',0));
		$book = Book::where('id',$id)->first();

		if($book) {

			$pages 		= Page::where('book_id', $book->id)->get();

			$users_id 	= [];

			foreach($pages as $p)
			{
				$users_id[] = $p->user_id;
			}

			$users = User::whereIn('id', $users_id)->take($max)->skip($offset)->get();
			
			foreach ($users as $u) {
				$u->validAuthor = false;
				$userPages 		= Page::where('book_id', $book->id)->where('user_id', $u->id)->get();
				foreach ($userPages as $up) {
					if($up->status == 1)
					{
						$u->validAuthor = true;
					}
				}
			}
			if(count($users) > 0) {
				return Response::json(
					array(
						'items' 	=> $users->toArray(),
						'metadata'	=> array(
							'max'		=> $max,
							'offset'	=> $offset,
							'error'		=> false
							)
						),
					200
				);
				
			}
			else {
				return Response::json(
					array(
						'metadata'	=> array(
							'error' 	=> true,
							'message' 	=> 'No contributors found'
						)
					),
					404
				);
			}

		}
		else {
			return Response::json(
				array(
					'metadata'	=> array(
						'error' 	=> true,
						'message' 	=> 'No book found'
					)
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
					'metadata'	=> array(
						'error' 	=> false
					),
					'username'		=> $user->username,
					'id'			=> $user->id,
					'created_at'	=> $user->created_at
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'metadata'	=> array(
						'error' 	=> true,
						'message' 	=> 'User not found'
					)
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
					'metadata'	=> array(
						'error' 	=> false,
						'message'	 => 'User deleted'
					)
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
					'metadata'	=> array(
						'error' 	=> true,
						'message' 	=> 'User not deleted'
					)
				),
				404
			);
		}
		
	}

}