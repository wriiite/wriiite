<?php
 
class BookTableSeeder extends Seeder {
 
	public function run()
	{
		DB::table('books')->truncate();

		Book::create(array(
			'user_id' => 1,
			'title' => 'First Book',
			'slug' => 'first-book',
			'description' => 'This is the first book, alpha of litterature, a never ending book',
			'status' => true
		));

		Book::create(array(
			'user_id' => 5,
			'slug' => 'amphitruo',
			'title' => 'Amphitruo',
			'description' => 'Ἀμφιτρύων is a Latin play for the early Roman theatre by playwright Titus Maccius Plautus. It is Plautus’ only play on a mythological subject; he refers to it as a tragicomoedia (tragic comedy) in the prologue. It includes Amphitryon’s jealous and confused reaction to Alcmena’s seduction by Jupiter, and ends with the birth of Hercules.',
			'status' => true
		));

		Book::create(array(
			'user_id' => 3,
			'slug' => 'dramatis-personae',
			'title' => 'Dramatis Personae',
			'description' => 'This one is in english',
			'status' => true
		));

		Book::create(array(
			'user_id' => 3,
			'slug' => 'livre-test-publish',
			'title' => 'Livre Test Publish',
			'description' => 'This book is used to test the publish function in the bookController',
			'status' => false
		));

	}
 
}