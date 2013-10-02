<?php

class BookController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$pagination 	= Input::get('p',1);
		$item_perPage 	= 2;
		$books 			= Book::orderBy('created_at', 'desc')->forPage($pagination,$item_perPage)->get();
			
		if($books) {
			return Response::json(
				array(
					'error' 		=> false,
					'books' 		=> $books->toArray(),
					'pagination'  	=> $pagination 
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'error' 	=> true,
					'message' 	=> 'There\'s no books here'
				),
				404
			);
		}
	}

	/**
	 * display a list of books by user
	 *
	 * @param int $id 
	 * @return Response
	 * @author gaspard
	 */
	public function ownedByUser($id)
	{
		$books = Book::where('user_id', $id)->get();
			
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

			$slug			= Str::slug(Request::get('title'));
			$existSlug		= Book::where('slug',$slug)->get();

			if(count($existSlug) > 0) {
				return Response::json(
					array(
						'error' 	=> true,
						'message' 	=> 'This title is already taken'
					),
					404
				);
			}
			else {
				$book 				= new Book;
				$book->title 		= Request::get('title');
				$book->slug 		= $slug;
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

			$pages		= Page::where('book_id',$book->id)->get();
			$pageArray 	= [];

			foreach($pages as $p)
			{
				$pageArray[]		= array(
					'id'			=> $p->id,
					's'				=> $p->status,
					'n'				=> $p->number,
					'user'			=> array('username'=>$p->user->username,'id'=>$p->user->id)
					);
			}

			return Response::json(
				array(
					'error' 		=> false,
					'title'			=> $book->title,
					'slug'			=> $book->slug,
					'description' 	=> $book->description,
					'created_at'	=> $book->created_at,
					'modified_at'	=> $book->modified_at,
					'user'			=> array('id'=>$book->user->id,'username'=>$book->user->username),
					'pages'			=> $pageArray
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

			if(isset($errors['description'])) {

				return Response::json(
					array(
						'error' 	=> true,
						'message' 	=> 'The description isn\'t long enough'
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
					'error' 	=> true,
					'message' 	=> 'Book doesn\'t exist'
				),
				404
			);
		}
		
	}

}