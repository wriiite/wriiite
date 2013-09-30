<?php

class Page extends Eloquent {

	protected $table = 'pages';

	public function book() {
		return $this->belongsTo('Book');
	}

	public function user() {
		return $this->belongsTo('User');
	}

}