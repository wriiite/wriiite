<?php

class PageController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$page 			= Input::get('page',1);
		$item_perPage 	= 4;
		$pages 			= Page::orderBy('created_at', 'desc')->forPage($page,$item_perPage)->get();
			
		if($pages) {
			return Response::json(
				array(
					'error' => false,
					'pages' => $pages->toArray(),
					'page'  => $page 
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'error' 	=> true,
					'message' 	=> 'There\'s no pages here'
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
		$rules = array(
		        'content' 		=> 'required|between:300,350',
		        'book_id' 		=> 'required|exists:books,id'
		    );


		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
		   return Response::json(
				array(
					'error' 	=> true,
					'message' 	=> 'The page creation failed'
				),
				404
			);
		}

		else {

			$parent 			= Page::where('book_id',$book_id)->orderBy('created_at', 'desc')->where('status',1)->first();
			$status				= 0; //Define the status of the created page

			if(!$parent){

				//If it's the first page of the book

				$parent 		= new Page;
				$parent->id 	= 0;
				$parent->number = 0; 
				$status 		= 1; //if there's no parent page, the new page is the first - auto validated - page of the book.
			}

			$page 				= new Page;
			$page->content 		= Request::get('content');
			$page->book_id 		= Request::get('book_id');
			$page->parent_id	= $parent->id;
			$page->number 		= $parent->number + 1;
			$page->user_id		= Auth::user()->id;
			$page->status 		= $status;



			$page->save();

			return Response::json(
				array(
					'error' => false,
					'page' 	=> $page->toArray()
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
		$page = Page::where('id','=', $id)->first();

		if($page) {
			return Response::json(
				array(
					'error' => false,
					'page' => $page->toArray()
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
		$page = Page::where('id', $id)->where('status', 0)->first();

		if($page) {

			if($page->book->user_id == Auth::user()->id) {

				$page->status = 1;
				$page->save();

				return Response::json(
					array(
						'error' => false,
						'page' 	=> $page->toArray()
					),
					200
				);
			}
			else {
				return Response::json(
					array(
						'error' 	=> true,
						'message' 	=> 'You are not the owner of the book'
					),
					404
				);
			}

			
		}
		else {
			return Response::json(
				array(
					'error' 	=> true,
					'message' 	=> 'Page not found'
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
		//
	}

}