<?php

class PageController extends \BaseController {
	
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
		$pages 	= Page::orderBy('created_at', 'desc')->take($max)->skip($offset)->get();

		if($pages && count($pages) > 0) {
			return Response::json(
				array(
					'items' 	=> $pages->toArray(),
					'metadata' 	=> array(
						'max' 		=> $max,
						'offset' 	=> $offset,
						'error' 	=> false
					)
				),
				200
			);
		}
		elseif(count($pages) == 0) {
			return Response::json(
				array(
					'items' 	=> [],
					'metadata' 	=> array(
						'max' 		=> $max,
						'offset' 	=> $offset,
						'error' 	=> false,
						'message' 	=> 'No pages found'
					)
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'metadata' 	=> array(
						'error' 	=> true,
						'message' 	=> 'No pages found, unknown error'
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
		$rules = array(
			'content' 		=> 'required|between:300,350',
			'book_id' 		=> 'required|exists:books,id'
		);

		$validator = Validator::make(Input::all(), $rules);

		if($validator->fails()) {

			return Response::json(
				array(
					'metadata'	=> array(
						'error' 	=> true,
						'message' 	=> 'The page must be between 300 and 350 characters'
					)
					
				),
				400
			);
		}
		else {

			$book 				= Book::find(Input::get('book_id'));
			$content			= Input::get('content');

			if($book->status == 1){
				$parent 		= Page::where('book_id',$book->id)->where('status',1)->orderBy('id', 'desc')->first();
				$parent_id 		= $parent->id;
				$parent_number	= $parent->number;
				$status			= 0; //Define the status of the created page
			}
			else{
				//If it's the first page of the book
				$parent_id 		= 0;
				$parent_number	= 0;
				$status 		= 1; //if there's no parent page, the new page is the first - auto validated - page of the book.
				if($book->user_id != Auth::user()->id) {
					return Response::json(
						array(
							'metadata'	=> array(
								'error' 	=> true,
								'message' 	=> 'You have to be the author of a book to write the first page.'
							)
							
						),
						403
					);
				}
			}

			$page 				= new Page;
			$page->content 		= $content;
			$page->book_id 		= $book->id;
			$page->parent_id	= $parent_id;
			$page->number 		= $parent_number + 1;
			$page->user_id		= Auth::user()->id;
			$page->status 		= $status;
			$page->save();

			$stored 	= $page->toArray();
			$metadata 	= array(
				'metadata'	=> array(
					'error'	=> false
				)
			);

			return Response::json(
					array_merge($stored,$metadata),
					201
				);
		}
	}


	/**
	 * display a list of pages by user
	 *
	 * @param int $id 
	 * @return Response
	 * @author gaspard
	 */
	public function ownedByUser($id)
	{
		$max 	= intval(Input::get('max',10));
		$offset = intval(Input::get('offset',0));
		$pages 	= Page::orderBy('created_at', 'desc')->where('user_id', $id)->take($max)->skip($offset)->get();
			
		if($pages && count($pages) > 0) {
			return Response::json(
				array(
					'items' 	=> $pages->toArray(),
					'metadata' 	=> array(
						'max' 		=> $max,
						'offset' 	=> $offset,
						'error' 	=> false
					)
				),
				200
			);
		}
		elseif (count($pages) == 0) {
			return Response::json(
				array(
					'items' 	=> [],
					'metadata' 	=> array(
						'max' 		=> $max,
						'offset' 	=> $offset,
						'error' 	=> false,
						'message' 	=> 'No pages found'
					)
				),
				200 
			);
		}
		else {
			return Response::json(
				array(
					'metadata' 	=> array(
						'max' 		=> $max,
						'offset' 	=> $offset,
						'error' 	=> true
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
		$page = Page::where('id','=', $id)->first();

		if($page){

			$altPages 	= Page::where('book_id',$page->book_id)->where('number',$page->number)->where('id', '!=', $page->id)->get();

			if($altPages) {
				$altPagesArray 	= [];
				foreach($altPages as $a) {
					$altPagesArray[] = array(
						'id'	=> $a->id,
						's'		=> $a->status,
						'user'	=> array('id'=>$a->user->id,'username'=>$a->user->username)
					);
				}
			}
			else {
				$altPagesArray 		= [];
			}

			return Response::json(
				array(

					'error' 		=> false,
					'id'			=> $page->id,
					'user'			=> array('id'=>$page->user->id,'username'=>$page->user->username),
					'parent_id'		=> $page->parent_id,
					'book_id'		=> $page->book_id,
					'number'		=> $page->number,
					'status'		=> $page->status,
					'content'		=> $page->content,
					'alt'			=> $altPagesArray,
					'created_at'	=> $page->created_at,
					'updated_at'	=> $page->updated_at,
					'metadata'		=> array(
						'error' 		=> false
					)
				),
				200
			);
		}
		else {
			return Response::json(
				array(
					'metadata' => array(
						'error' 	=> true,
						'message' 	=> 'Page not found'
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
		$page = Page::where('id', $id)->where('status', 0)->first();

		if($page){

			if($page->book->user_id == Auth::user()->id) {

				$validPage = Page::where('book_id',$page->book_id)->where('number',$page->number)->where('status',1)->get();

				//Here, we check if there's already a validated page for this page number. If so : 404

				if(count($validPage) > 0) {
					return Response::json(
						array(
							'metadata' => array(
								'error' 	=> true,
								'message' 	=> 'There\'s already a validated page for this book\'s page'
							)
						),
						400
					);
				}
				else {
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
				
			}
			else {
				return Response::json(
					array(
						'metadata' => array(
							'error' 	=> true,
							'message' 	=> 'You\'re not the owner of the book'
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
							'message' 	=> 'Page not found or already validated'
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
		//
	}

}