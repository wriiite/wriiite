<?php
 
class Book extends Eloquent {

	protected $table = 'books';

	public function pages() {
		return $this->hasMany('Page');
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

	/*==============================
				TODO
	================================

	Create a new model to generalize and simplify across all models the mutators on IDs Attributes (book_id, user_id, id ...).

	*/


}