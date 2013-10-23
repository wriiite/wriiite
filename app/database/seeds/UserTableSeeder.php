<?php
 
class UserTableSeeder extends Seeder {
 
	public function run()
	{
		DB::table('users')->truncate();

		User::create(array(
			'username' 	=> 'firstuser',
			'password' 	=> Hash::make('first_password'),
			'email' 	=> 'firstuser@yopmail.com',
			'api_key'	=> Hash::make('firstuser')
		));

		User::create(array(
			'username' 	=> 'seconduser',
			'password' 	=> Hash::make('second_password'),
			'email' 	=> 'seconduser@yopmail.com',
			'api_key'	=> Hash::make('seconduser')
		));

		User::create(array(
			'username' 	=> 'jonathan',
			'password' 	=> Hash::make('jonathan'),
			'email' 	=> 'jonatan@yopmail.com',
			'api_key'	=> Hash::make('jonathan')
		));

		User::create(array(
			'username' 	=> 'gaspard',
			'password' 	=> Hash::make('gaspard'),
			'email' 	=> 'gaspard@yopmail.com',
			'api_key'	=> Hash::make('gaspard')
		));

		User::create(array(
			'username' 	=> 'plautus', // Plautus Titus Maccius
			'password' 	=> Hash::make('plautus'),
			'email' 	=> 'plautus@yopmail.com',
			'api_key'	=> Hash::make('plautus')
		));

	}
 
}