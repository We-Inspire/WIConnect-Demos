<?php
class Post extends Eloquent {
	
	protected $table = "posts";
	protected $softDelete = true;

	public static function boot() {

		parent::boot();

		/*$this::updated(function($post) {



		});*/

	}

}
?>
