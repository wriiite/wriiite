<?php

class Page extends Eloquent {

	protected $table = 'pages';

	public function book() {
		return $this->belongsTo('Book');
	}

	public function user() {
		return $this->belongsTo('User');
	}

	public function getIdAttribute($value) {
		return (int) $value;
	}

	public function getUserIdAttribute($value) {
		return (int) $value;
	}

	public function getBookIdAttribute($value) {
		return (int) $value;
	}

}