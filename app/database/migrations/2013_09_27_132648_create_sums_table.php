<?php

// Sum and Sums
// is easier than Summary and Summaries

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSumsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*
			TODO there should be a manytomany pages_has_sums
		*/
		Schema::create('sums', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('book_id');
			$table->text('content');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sums');
	}

}
