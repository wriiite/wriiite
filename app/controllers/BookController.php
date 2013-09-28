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
 
		return Response::json(
			array(
				'error' => false,
				'books' => $books->toArray()
			),
			200
		);
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
		        'title' => 'required',
		        'description' => 'required'
		    );


		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
		   // Error pending...418 ?
		}

		else 
		{
			$book = new Book;
			$book->title = Request::get('title');
			$book->slug = uniqid();
			$book->description = Request::get('description');
			$book->user_id = Auth::user()->id;
			$book->status = false;
			$book->save();

			return Response::json(
				array(
					'error' => false,
					'book' => $book->toArray()
				),
				200
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

		$book = Book::where('id','=', $id)
			->first();

		if($book)
		{
			return Response::json(
				array(
					'error' => false,
					'book' => $book->toArray()
				),
				200
			);
		}
		else 
		{
			return Response::json(
				array(
					'error' => true,
					'message' => 'Book not found'
				),
				400
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
		$modified = [];
		$book = Book::where('user_id', Auth::user()->id)->find($id);

		if ( Request::get('title') ) {
			$book->title = Request::get('title');
			$modified['title'] = Request::get('title');
		}

		if ( Request::get('description') ) {
			$book->description = Request::get('description');
			$modified['description'] = Request::get('description');
		}

		$book->save();

		return Response::json(
			array(
				'error' => false,
				'modified' => $modified,
				'message' => 'url updated'
			),
			200
		);
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

		$book->delete();

		return Response::json(
			array(
				'error' => false,
				'message' => 'book deleted'
			),
			200
		);
	}

}