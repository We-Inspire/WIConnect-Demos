<?php
class Chat extends Eloquent {
	
	protected $table = "chats";
	protected $softDelete = true;

	public static function boot() {

		parent::boot();

		Chat::observe(new ChatObserver());

		/*$this::updated(function($post) {



		});*/

	}

	public function chatUser(){
		return $this->belongsTo("ChatUser","user_id");
	}

}
?>
