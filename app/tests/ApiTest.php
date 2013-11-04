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
		$this->assertTrue($response->getStatusCode() == 404);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);

		// insert fail no title
		$response = $this->call('POST', '/api/v1/books', 
			array('description' => 'My failing book is about a book that fails, because some title is missing')
		);
		$this->assertTrue($response->getStatusCode() == 404);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);

		// insert fail decription too short
		$response = $this->call('POST', '/api/v1/books', 
			array('title' => 'My short book', 'description' => 'This is too short')
		);
		$this->assertTrue($response->getStatusCode() == 404);
		$j = json_decode($response->getContent());
		$this->assertTrue($j->metadata->error);

		// update
		$response = $this->call('PUT', '/api/v1/books/'.$id, ['title'=> 'My New book title, updated']);
		$this->assertTrue($response->getStatusCode() == 200);
		$j = json_decode($response->getContent());
		$this->assertFalse($j->metadata->error);
		$this->assertEquals('My New book title, updated', $j->title);

		// update fail description too short
		$response = $this->call('PUT', '/api/v1/books/'.$id, 
			array('title' => 'My short book', 'description' => 'This is too short')
		);
		$this->assertTrue($response->getStatusCode() == 204);

		// update
		$response = $this->call('PUT', '/api/v1/books/'.$id, ['description'=> 'This is my new book, you should enjoy it very much']);
		$this->assertTrue($response->getStatusCode() == 200);
		$j = json_decode($response->getContent());
		$this->assertEquals('This is my new book, you should enjoy it very much', $j->description);

		// read
		$response = $this->call('GET', '/api/v1/books/'.$id);
		$j = json_decode($response->getContent());
		$this->assertEquals('My New book title, updated', $j->title);
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
		return;
		$response = $this->call('GET', '/api/v1/users/1/books');
		$j = json_decode($response->getContent());

		$this->assertEquals(1, $j->books[0]->id);
		$this->assertEquals('first-book', $j->items[0]->slug);
		$this->assertEquals('First Book', $j->items[0]->title);
		$this->assertEquals('This is the first book, alpha of litterature, a never ending book', $j->books[0]->description);
	}

	/**
	 * Test API User Read
	 *
	 * @author gaspard
	 */
	public function testApiUserRead()
	{
		return;
		$response = $this->call('GET', '/api/v1/user');
		$j = json_decode($response->getContent());
		$this->assertEquals(1, $j->users[0]->id);
		$this->assertEquals('firstuser', $j->users[0]->username);
		$this->assertTrue(isset($j->users[0]->username));
		$this->assertFalse(isset($j->users[0]->email));
		$this->assertFalse(isset($j->users[0]->password));

		// first user
		$response = $this->call('GET', '/api/v1/user/1');
		$j = json_decode($response->getContent());
		$this->assertEquals(1, $j->user->id);
		$this->assertEquals('firstuser', $j->user->username);
		$this->assertTrue(isset($j->user->username));
		$this->assertFalse(isset($j->user->email));
		$this->assertFalse(isset($j->user->password));

		// second user
		$response = $this->call('GET', '/api/v1/user/2');
		$j = json_decode($response->getContent());
		$this->assertEquals(2, $j->user->id);
		$this->assertEquals('seconduser', $j->user->username);

	}
}