<?php

class BookController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$books = Book::where('user_id', Auth::user()->id)->get();
 			
 		if($books) {
			return Response::json(
				array(
					'error' => false,
					'books' => $books->toArray()
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'error' 	=> true,
					'message' 	=> 'This user has no books yet'
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

		/*
			TODO 
			test it with
			curl -i --user gaspard:gaspard -d 'title=A title&description=A description' http://wriiite.site:8080/api/v1/book
		*/


		$rules = array(
		        'title' 		=> 'required|min:3',
		        'description' 	=> 'required|min:30'
		    );


		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
		   return Response::json(
				array(
					'error' 	=> true,
					'message' 	=> 'The book creation failed'
				),
				404
			);
		}

		else {
			$book 				= new Book;
			$book->title 		= Request::get('title');
			$book->slug 		= uniqid();
			$book->description 	= Request::get('description');
			$book->user_id 		= Auth::user()->id;
			$book->status 		= false;
			$book->save();

			return Response::json(
				array(
					'error' => false,
					'book' 	=> $book->toArray()
				),
				201
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

		$book = Book::where('id','=', $id)->first();

		if($book) {
			return Response::json(
				array(
					'error' => false,
					'book' => $book->toArray()
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
		$modified 	= [];
		$errors 	= [];
		$book 		= Book::where('user_id', Auth::user()->id)->find($id);

		if($book) {

			if ( Request::get('title') ) {

				$validator = Validator::make(Input::all(), array('title' => 'required|min:3'));
				if($validator->fails()) {
					$errors['title'] = true;
				}

				else {
					$book->title 		= Request::get('title');
					$modified['title'] 	= Request::get('title');
				}
				
			}

			if ( Request::get('description') ) {

				$validator = Validator::make(Input::all(), array('description' => 'required|min:30'));
				if($validator->fails()) {
					$errors['description'] = true;
				}
				else {
				$book->description 			= Request::get('description');
				$modified['description'] 	= Request::get('description');
				}
			}

			// If validation doesn't pass

			if(isset($errors['description']) OR isset($errors['title'])) {

				return Response::json(
					array(
						'error' 	=> true,
						'message' 	=> 'The title and/or the description isn\'t long enough'
					),
					204
				);

			}

			// Else, we can update the book

			else {
				$book->save();

				return Response::json(
					array(
						'error' 	=> false,
						'modified' 	=> $modified,
						'message' 	=> 'Book updated'
					),
					200
				);
			}
		}
		else {
			return Response::json(
				array(
					'error' 	=> true,
					'message' 	=> 'Book doesn\'t exist'
				),
				404
			);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$book = Book::where('user_id', Auth::user()->id)->find($id);

		if($book) {
			$book->delete();

			return Response::json(
				array(
					'error' 	=> false,
					'message'	 => 'book deleted'
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'error' => true,
					'message' => 'Book doesn\'t exist'
				),
				404
			);
		}
		
	}

}