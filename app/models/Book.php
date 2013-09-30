<?php
 
class Book extends Eloquent {

	protected $table = 'books';

	public function pages() {
		return $this->hasMany('Page');
	}

	public function user() {
		return $this->belongsTo('User');
	}

}