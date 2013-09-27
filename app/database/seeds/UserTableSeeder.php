<?php
 
class UserTableSeeder extends Seeder {
 
	public function run()
	{
		DB::table('users')->truncate();

		User::create(array(
			'username' => 'firstuser',
			'password' => Hash::make('first_password'),
			'email' => 'firstuser@yopmail.com'
		));

		User::create(array(
			'username' => 'seconduser',
			'password' => Hash::make('second_password'),
			'email' => 'seconduser@yopmail.com'
		));

		User::create(array(
			'username' => 'jonathan',
			'password' => Hash::make('jonathan'),
			'email' => 'jonatan@yopmail.com'
		));

		User::create(array(
			'username' => 'gaspard',
			'password' => Hash::make('gaspard'),
			'email' => 'gaspard@yopmail.com'
		));

		User::create(array(
			'username' => 'plautus', // Plautus Titus Maccius
			'password' => Hash::make('plautus'),
			'email' => 'plautus@yopmail.com'
		));

	}
 
}