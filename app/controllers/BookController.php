<?php

class BookController extends \BaseController {

	public function __construct() {
		$this->beforeFilter('auth.basic', array('except' => array('index', 'show', 'ownedByUser')));
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
		$books 	= Book::orderBy('created_at', 'desc')->take($max)->skip($offset)->get();

		if($books && count($books) > 0) {
			return Response::json(
				array(
					'items' => $books->toArray(),
					'metadata' => array(
						'max' => $max,
						'offset' => $offset,
						'error' => false
					)
				),
				200
			);
		}
		elseif(count($books) == 0) {
			return Response::json(
				array(
					'items' => [],
					'metadata' => array(
						'max' => $max,
						'offset' => $offset,
						'error' => false,
						'message' => 'No books found'
					)
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'metadata' => array(
						'error' => true,
						'message' => 'No books found, unknown error'
					)
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
		$max 	= intval(Input::get('max',10));
		$offset = intval(Input::get('offset',0));
		$books 	= Book::orderBy('created_at', 'desc')->where('user_id', $id)->take($max)->skip($offset)->get();
			
		if($books && count($books) > 0) {
			return Response::json(
				array(
					'items' => $books->toArray(),
					'metadata' => array(
						'max' => $max,
						'offset' => $offset,
						'error' => false,
						'user_id' => $id
					)
				),
				200
			);
		}
		elseif (count($books) == 0) {
			return Response::json(
				array(
					'items' => [],
					'metadata' => array(
						'max' => $max,
						'offset' => $offset,
						'error' => false,
						'message' => 'No books found'
					)
				),
				200 // no content, wanna check best practices
						// http://benramsey.com/blog/2008/05/http-status-204-no-content-and-205-reset-content/
			);
		}
		else {
			return Response::json(
				array(
					'metadata' => array(
						'max' => $max,
						'offset' => $offset,
						'error' => true
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
					'metadata' => array(
						'error' 	=> true,
						'message' 	=> 'The book creation has failed'
					)
				),
				400
			);
		}

		else {

			$slug			= Str::slug(Request::get('title'));
			$existSlug		= Book::where('slug',$slug)->get();

			if(count($existSlug) > 0) {
				return Response::json(
					array(
						'metadata' => array(
							'error' 	=> true,
							'message' 	=> 'This title is already taken'
						)
					),
					400
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

				$stored = $book->toArray();
				$metadata = array(
					'metadata' => array(
						'error' => false,
					)
				);
				return Response::json(
					array_merge($stored,$metadata),
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
					'content'		=> $p->content,
					'user'			=> array('username'=>$p->user->username,'id'=>$p->user->id)
					);
			}

			return Response::json(
				array(
					'metadata'	=> array(
						'error' 		=> false
					),
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
					'metadata' => array(
						'error' 	=> true,
						'message' 	=> 'Book not found'
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
		$updated 	= [];
		$errors 	= [];
		$book 		= Book::find($id);

		if(count($book)) {

			if($book->user_id == Auth::user()->id)
			{
				if ( Request::get('description') ) {

					$validator = Validator::make(Input::all(), array('description' => 'required|min:30'));
					if($validator->fails()) {
						$errors['description'] = true;
					}
					else {
						$book->description 			= Request::get('description');
						$updated['description'] 	= Request::get('description');
					}
				}


				if ( Request::get('title') ) {
					$errors['title'] = true;
				}

				// If trying to update the title

				if(isset($errors['title'])) {

					return Response::json(
						array(
							'metadata' => array(
								'error' 	=> true,
								'message' 	=> 'The title can not be updated'
							)
						),
						403 // access denied
					);
				}
				// If validation doesn't pass

				if(isset($errors['description'])) {

					return Response::json(
						array(
							'metadata' => array(
								'error' 	=> true,
								'message' 	=> 'The description too short'
							)
						),
						400
					);
				}

				// Else, we can update the book

				else {
					$book->save();

					$metadata = array(
						'metadata' => array(
							'error' 	=> false,
							'message' 	=> 'Book updated'
						)
					);

					return Response::json(
						array_merge($updated,$metadata),
						200
					);
				}
			}
			else {
				return Response::json(
					array(
						'metadata' => array(
							'error' 	=> true,
							'message' 	=> 'You\'re not the owner of this book'
						)
					),
					403
				);
			}
		}
		else {
			return Response::json(
				array(
					'metadata' => array(
						'error' 	=> true,
						'message' 	=> 'Book does not exist'
					)
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
					'metadata'=>	array(
						'error' 	=> false,
						'message'	 => 'book deleted'
					),
					'id' => $id
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'metadata'=>	array(
						'error' 	=> true,
						'message'	 => 'book may have not been deleted'
					)
				),
				404
			);
		}
		
	}
}