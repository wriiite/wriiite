<?php

class ApiTest extends TestCase {

	/**
	 * is api alive ?
	 *
	 * @return void
	 * @author gaspard
	 */
	public function testApiAlive()
	{
		$response = $this->call('GET', '/api/v1');
		$this->assertTrue($response->isOk());
		$this->assertEquals('API v0.2 is alive', json_decode($response->getContent())->message );
	}

	/**
	 * test API Simple Requests
	 *
	 * @return void
	 */
	public function testApiSimpleRequests()
	{
		$this->seed();
		
//		Route::enableFilters();
//		$crawler = $this->client->request('GET', '/api/v1/book');
//		$this->assertTrue($this->client->getResponse()->isOk());
	}
	/**
	 * test API Book Read
	 * Can we read the API Book
	 * @return void
	 * @author gaspard
	 */
	public function testApiBookRead()
	{

		$response = $this->call('GET', '/api/v1/books');
		$j = json_decode($response->getContent());

		$this->assertEquals(1, $j->items[0]->id);
		$this->assertEquals('first-book', $j->items[0]->slug);
		$this->assertEquals('First Book', $j->items[0]->title);
		$this->assertEquals('This is the first book, alpha of litterature, a never ending book', $j->items[0]->description);


		$response = $this->call('GET', '/api/v1/books?max=2');
		$j = json_decode($response->getContent());

		$this->assertEquals(2, count($j->items));

		$response = $this->call('GET', '/api/v1/books?offset=2');
		$j = json_decode($response->getContent());

		$this->assertEquals('dramatis-personae', $j->items[0]->slug);
		$this->assertEquals('Dramatis Personae', $j->items[0]->title);

		$response = $this->call('GET', '/api/v1/books?offset=99999');
		$j = json_decode($response->getContent());

		$this->assertEquals(0, count($j->items));

		$response = $this->call('GET', '/api/v1/books/1');
		$j = json_decode($response->getContent());

		//		$this->assertEquals(1, $j->id);
		$this->assertEquals('first-book', $j->slug);
		$this->assertEquals('First Book', $j->title);
		$this->assertEquals('This is the first book, alpha of litterature, a never ending book', $j->description);

	}



	/**
	 * test API Book Write
	 * 
	 * @return void
	 * @author gaspard
	 */
	public function testApiBookWrite()
	{
/*		$t = 'testuser'.uniqid();
		$user = new User(array('name' => $t, 'password' => $t, 'email'=> $t.'@mail.com'));
*/
		// filters are disactivated in laravel while testing
		// we have to simulate being some user
		$user = new User(array('id'=>1));
		$this->be($user);

		// insert
		$response = $this->call('POST', '/api/v1/books', 
			array('title' => 'My New book title', 'description' => 'This is my new book, you should enjoy it')
		);
		$this->assertTrue($response->getStatusCode() == 201);

		// this *might* be after a redirection
		$j = json_decode($response->getContent());
		$this->assertEquals('My New book title', $j->title);
		$this->assertEquals('This is my new book, you should enjoy it', $j->description);

		$this->assertGreaterThan(1, $j->id);

		// inserting should return an id
		$id = $j->id;

		// read
		$response = $this->call('GET', '/api/v1/books/'.$id);
		$j = json_decode($response->getContent());
		$this->assertEquals('My New book title', $j->title);
		$this->assertEquals('This is my new book, you should enjoy it', $j->description);

		// insert fail no description
		$response = $this->call('POST', '/api/v1/books', 
			array('title' => 'My failing book title')
		);
		$this->assertTrue($response->getStatusCode() == 400);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);

		// insert fail no title
		$response = $this->call('POST', '/api/v1/books', 
			array('description' => 'My failing book is about a book that fails, because some title is missing')
		);
		$this->assertTrue($response->getStatusCode() == 400);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);

		// insert fail decription too short
		$response = $this->call('POST', '/api/v1/books', 
			array('title' => 'My short book', 'description' => 'This is too short')
		);
		$this->assertTrue($response->getStatusCode() == 400);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);

		// update description
		$response = $this->call('PUT', '/api/v1/books/'.$id, ['description'=> 'This is my new book, you should enjoy it very much']);
		$this->assertTrue($response->getStatusCode() == 200);
		$j = json_decode($response->getContent());
		$this->assertEquals('This is my new book, you should enjoy it very much', $j->description);

		// only the description has been updated
		$response = $this->call('GET', '/api/v1/books/'.$id);
		$j = json_decode($response->getContent());
		$this->assertEquals('My New book title', $j->title);
		$this->assertEquals('This is my new book, you should enjoy it very much', $j->description);

		// update title is not allowed
		$response = $this->call('PUT', '/api/v1/books/'.$id, ['title'=> 'My New book title, updated']);
		$this->assertFalse($response->getStatusCode() == 200);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);


		// update fail description too short
		$response = $this->call('PUT', '/api/v1/books/'.$id, 
			array('description' => 'This is too short')
		);
		$this->assertTrue($response->getStatusCode() == 400);

		// read
		$response = $this->call('GET', '/api/v1/books/'.$id);
		$j = json_decode($response->getContent());
		$this->assertEquals('My New book title', $j->title);
		$this->assertEquals('This is my new book, you should enjoy it very much', $j->description);

	}

	/**
	 * testApiBookDelete
	 *
	 * @return void
	 * @author gaspard
	 */
	public function testApiBookDelete()
	{
		$user1 = new User(array('id'=>1));
		$user2 = new User(array('id'=>2));
		$this->be($user1);

		// insert a new book
		$response = $this->call('POST', '/api/v1/books', 
			array('title' => 'The ghost book', 'description' => 'This book will be deleted quite soon it should be more than 30 characters long')
		);
		$this->assertTrue($response->getStatusCode() == 201);
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);
		$this->assertGreaterThan(1, $j->id);

		$id = $j->id;

		// consult it, it should exist
		$response = $this->call('GET', '/api/v1/books/'.$id);
		$this->assertTrue($response->getStatusCode() == 200);
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);

		// delete it
		$response = $this->call('DELETE', '/api/v1/books/'.$id);
		$this->assertTrue($response->getStatusCode() == 200);
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);

		// consult it, it should not exist
		$response = $this->call('GET', '/api/v1/books/'.$id);
		$this->assertTrue($response->getStatusCode() == 404);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);


		// fail to delete it (already deleted)
		$response = $this->call('DELETE', '/api/v1/books/'.$id);
		$this->assertTrue($response->getStatusCode() == 404);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);


		// insert a new book
		$response = $this->call('POST', '/api/v1/books', 
			array('title' => 'The survival book', 'description' => 'This book will not be deleted because of controller restrictions')
		);

		$this->assertTrue($response->getStatusCode() == 201);
		$j = json_decode($response->getContent());
		$id2 = $j->id;

		// change user
		$this->be($user2);

		// fail to delete it (ownership)
		$response = $this->call('DELETE', '/api/v1/books/'.$id2);
		$this->assertTrue($response->getStatusCode() == 404);

	}

	/**
	 * Test API Book By User
	 *
	 * @author gaspard
	 */
	public function testApiBookByUser()
	{
		// books by users
		$response = $this->call('GET', '/api/v1/users/1/books');
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);
		$this->assertEquals('integer', gettype($j->items[0]->id));

		$first = 1; // looking for first-book

		for ($i=0; $i < count($j->items); $i++) { 
			if($j->items[$i]->id == $first)
				$x = $i;
		}

		// $j->items[$x] is the first book

		$this->assertEquals($first, $j->items[$x]->id);
		$this->assertFalse($j->metadata->error);
		$this->assertEquals('first-book', $j->items[$x]->slug);
		$this->assertEquals('First Book', $j->items[$x]->title);
		$this->assertEquals('This is the first book, alpha of litterature, a never ending book', $j->items[$x]->description);

		// no books for this user
		$response = $this->call('GET','/api/v1/users/2/books');
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);
		$this->assertEquals(0,count($j->items));



	}

	/**
	 * Test API User Read
	 *
	 * @author gaspard
	 */
	public function testApiUserRead()
	{
		$response = $this->call('GET', '/api/v1/users');
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);

		$first = 1; // looking for firstuser

		for ($i=0; $i < count($j->items); $i++) { 
			if($j->items[$i]->id == $first)
				$x = $i;
		}

		// $j->items[$x] is the first user

		$this->assertEquals(1, $j->items[$x]->id);
		$this->assertEquals('firstuser', $j->items[$x]->username);
		$this->assertTrue(isset($j->items[$x]->username));
		$this->assertFalse(isset($j->items[$x]->email));
		$this->assertFalse(isset($j->items[$x]->password));
		$this->assertFalse(isset($j->items[$x]->api_key));

		// first user
		$response = $this->call('GET', '/api/v1/users/1');
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);
		$this->assertEquals(1, $j->id);
		$this->assertEquals('firstuser', $j->username);
		$this->assertTrue(isset($j->username));
		$this->assertFalse(isset($j->email));
		$this->assertFalse(isset($j->password));
		$this->assertFalse(isset($j->api_key));

		// second user
		$response = $this->call('GET', '/api/v1/users/2');
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);
		$this->assertEquals(2, $j->id);
		$this->assertEquals('seconduser', $j->username);

	}
	/**
	 * Test API User Write
	 *
	 * @author gaspard
	 */
	public function testApiUserWrite()
	{
		// users can't be updated this way for the moment
		// let's wait for auth

		// users can't be created this way for the moment
		// let's wait for auth
	}
	/**
	 * Test API User Delete
	 *
	 * @author gaspard
	 */
	public function testApiUserDelete()
	{
		// yet, users can delete themselves
		$user1 = new User(array('id'=>1));
		$user2 = new User(array('id'=>2));
		// the deletable user is 'deleteme' id 6
		$user6 = new User(array('id'=>6));
		$this->be($user6);

		// consult it, it should exist
		$response = $this->call('GET', '/api/v1/users/6');
		$this->assertTrue($response->getStatusCode() == 200);
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);

		// delete it
		$response = $this->call('DELETE', '/api/v1/users/6');
		$this->assertTrue($response->getStatusCode() == 200);
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);
		$this->assertEquals(6,$j->id);


		// consult it, it should not exist
		$response = $this->call('GET', '/api/v1/users/6');
		$this->assertTrue($response->getStatusCode() == 404);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);


		// fail to delete it (already deleted or the user might be logged out)
		$response = $this->call('DELETE', '/api/v1/users/6');
		$this->assertTrue($response->getStatusCode() == 404);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);

		// fail to delete, firstuser cannot delete seconduser
		$this->be($user1);
		$response = $this->call('DELETE', '/api/v1/users/2');
		$this->assertTrue($response->getStatusCode() == 404);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);

	}
	/**
	 * Test API Contributors
	 *
	 * @author gaspard
	 */
	public function testApiContributors()
	{
		// testing the UserController@bookContributors method
		$response = $this->call('GET', '/api/v1/books/1/users');
		$this->assertTrue($response->getStatusCode() == 200);
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);
		$this->assertGreaterThan(0,count($j->items));

		$this->assertEquals('string',gettype($j->items[0]->username));
	}

	/**
	 * Test API Page Read
	 *
	 * @author jonathan
	 */

	public function testApiPageRead()
	{
		$response = $this->call('GET', '/api/v1/pages');
		$j = json_decode($response->getContent());

		$this->assertEquals(3, $j->items[2]->id);
		$this->assertEquals('3', $j->items[2]->user_id);
		$this->assertEquals('This is an alternate page 2 by user 3', $j->items[2]->content);

		$response = $this->call('GET', '/api/v1/pages?max=2');
		$j = json_decode($response->getContent());

		$this->assertEquals(2, count($j->items));

		$response = $this->call('GET', '/api/v1/pages?offset=2');
		$j = json_decode($response->getContent());

		$this->assertEquals('This is an alternate page 2 by user 3', $j->items[0]->content);

		$response = $this->call('GET', '/api/v1/pages?offset=99999');
		$j = json_decode($response->getContent());

		$this->assertEquals(0, count($j->items));

		$response = $this->call('GET', '/api/v1/pages/2');
		$j = json_decode($response->getContent());

		//		$this->assertEquals(1, $j->id);
		$this->assertEquals('2', $j->user->id);
		$this->assertEquals('1', $j->book_id);
		$this->assertEquals('This is an alternate page 2 by user 2', $j->content);

	}

	/**
	 * test API Page Write
	 * 
	 * @return void
	 * @author jonathan
	 */
	public function testApiPageWrite()
	{
/*		$t = 'testuser'.uniqid();
		$user = new User(array('name' => $t, 'password' => $t, 'email'=> $t.'@mail.com'));
*/
		// filters are disactivated in laravel while testing
		// we have to simulate being some user
		$user = new User(array('id'=>1));
		$this->be($user);

		// insert
		$response = $this->call('POST', '/api/v1/pages', 
			array('book_id' => '1', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris at diam tempor, dignissim nunc placerat, euismod metus. Sed porttitor nulla vel felis congue luctus. Proin egestas nisi vitae tortor pulvinar vehicula. Suspendisse eleifend augue quis congue fringilla. Vestibulum pharetra urna sed nibh volutpat, vitae sed.')
		);
		$this->assertTrue($response->getStatusCode() == 201);

		// this *might* be after a redirection
		$j = json_decode($response->getContent());
		$this->assertEquals('1', $j->book_id);
		$this->assertEquals('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris at diam tempor, dignissim nunc placerat, euismod metus. Sed porttitor nulla vel felis congue luctus. Proin egestas nisi vitae tortor pulvinar vehicula. Suspendisse eleifend augue quis congue fringilla. Vestibulum pharetra urna sed nibh volutpat, vitae sed.', $j->content);

		$this->assertGreaterThan(1, $j->id);

		// inserting should return an id
		$id = $j->id;

		// read
		$response = $this->call('GET', '/api/v1/pages/'.$id);
		$j = json_decode($response->getContent());
		$this->assertEquals('2', $j->parent_id);
		$this->assertEquals('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris at diam tempor, dignissim nunc placerat, euismod metus. Sed porttitor nulla vel felis congue luctus. Proin egestas nisi vitae tortor pulvinar vehicula. Suspendisse eleifend augue quis congue fringilla. Vestibulum pharetra urna sed nibh volutpat, vitae sed.', $j->content);

		// insert fail no content
		$response = $this->call('POST', '/api/v1/pages', 
			array('content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris at diam tempor, dignissim nunc placerat, euismod metus. Sed porttitor nulla vel felis congue luctus. Proin egestas nisi vitae tortor pulvinar vehicula. Suspendisse eleifend augue quis congue fringilla. Vestibulum pharetra urna sed nibh volutpat, vitae sed.')
		);
		$this->assertTrue($response->getStatusCode() == 400);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);

		// insert fail no book id
		$response = $this->call('POST', '/api/v1/pages', 
			array('book_id' => '2')
		);
		$this->assertTrue($response->getStatusCode() == 400);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);

		// insert fail content too short
		$response = $this->call('POST', '/api/v1/pages', 
			array('book_id' => '1', 'content' => 'This is too short')
		);
		$this->assertTrue($response->getStatusCode() == 400);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);

		// insert fail book doesn't exist
		$response = $this->call('POST', '/api/v1/pages', 
			array('book_id' => '100', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris at diam tempor, dignissim nunc placerat, euismod metus. Sed porttitor nulla vel felis congue luctus. Proin egestas nisi vitae tortor pulvinar vehicula. Suspendisse eleifend augue quis congue fringilla. Vestibulum pharetra urna sed nibh volutpat, vitae sed.')
		);
		$this->assertTrue($response->getStatusCode() == 400);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);

		// create a new book 
		$response = $this->call('POST', '/api/v1/books', 
			array('title' => 'Empty Book', 'description' => 'This book will have no page at first, first we\'ll test if someone else than the author can write the missing first page, then we\'ll create the first page with the proper user')
		);
		$this->assertTrue($response->getStatusCode() == 201);
		$j = json_decode($response->getContent());


		//insert first page as the wrong user
		$user = new User(array('id'=>2));
		$this->be($user);

		$response = $this->call('POST', '/api/v1/pages', 
			array('book_id' => $j->id, 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris at diam tempor, dignissim nunc placerat, euismod metus. Sed porttitor nulla vel felis congue luctus. Proin egestas nisi vitae tortor pulvinar vehicula. Suspendisse eleifend augue quis congue fringilla. Vestibulum pharetra urna sed nibh volutpat, vitae sed.')
		);
		$this->assertTrue($response->getStatusCode() == 403);

		//insert first page as the wrong user
		$user = new User(array('id'=>1));
		$this->be($user);

		$response = $this->call('POST', '/api/v1/pages', 
			array('book_id' => $j->id, 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris at diam tempor, dignissim nunc placerat, euismod metus. Sed porttitor nulla vel felis congue luctus. Proin egestas nisi vitae tortor pulvinar vehicula. Suspendisse eleifend augue quis congue fringilla. Vestibulum pharetra urna sed nibh volutpat, vitae sed.')
		);
		$j = json_decode($response->getContent());
		$this->assertTrue($response->getStatusCode() == 201);
		$this->assertTrue($j->status == 1);

	}

		/**
	 * Test API Page By User
	 *
	 * @author jonathan
	 */
	public function testApiPageByUser()
	{

		$response = $this->call('GET', '/api/v1/users/2/pages');
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);
		//$this->assertEquals('integer', gettype($j->items[0]->id));

		$first = 2; // looking for the first page by user 2 (simpler content)

		for ($i=0; $i < count($j->items); $i++) { 
			if($j->items[$i]->id == $first)
				$x = $i;
		}

		// $j->items[$x] is the first book

		$this->assertEquals($first, $j->items[$x]->id);
		$this->assertFalse($j->metadata->error);
		$this->assertEquals('This is an alternate page 2 by user 2', $j->items[$x]->content);
		$this->assertEquals('1', $j->items[$x]->parent_id);
		$this->assertEquals('1', $j->items[$x]->book_id);

	}


}