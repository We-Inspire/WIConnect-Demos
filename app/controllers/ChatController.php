<?php

use WeInspire\Helpers\WIHelpers;

class ChatController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public $restful = true;

	public function getSpectator()
	{
		$subscriptions['chats'] = null;
		return View::make('chat.spectator')
			->with("subscriptions", $subscriptions);
	}

	public function getIndex()
	{
		$subscriptions['chats'] = null;
		$user = ChatUser::where('laravel_id',Session::getId())->first();
		return View::make('chat.index')
		->with("subscriptions", $subscriptions)
		->with("user", $user);;
	}

	public function postRegister() {

		$user = ChatUser::where('laravel_id',Session::getId())->first();
		if($user == null){
			$user = new ChatUser;
			$user->name = "";
			$user->laravel_id = Session::getId();
			$user->color = "hsl(".rand(100,300).", 55%, 65%)";
			$user->save();
		}
		WIHelpers::sendToNode(json_encode(array('nodeid' => Input::get('nodeid'), 'laravelid' => Session::getId())), "getUserSession");
		
		$chats = Chat::with('chatUser')->get();
		$data = array();
		$data['type'] = "create";
		$data['model'] = array();
		$data['model']['table'] = "chats";
		
		foreach ($chats as $chat) {	
			$data['model']['item'] = $chat->toArray();
			WIHelpers::sendToNode(WIHelpers::toJSON($data),"getData");
		}

	}

	public function postMessage(){
		$message = Input::get('message');
		$author = Input::get("author");

		$user = ChatUser::where('laravel_id',Session::getId())->first();
		//if(empty($user->name)){
			$rules = array(
				'message' => 'required',
				'author' => 'required'
			);
		/*}else{
			$rules = array(
				'message' => 'required'
			);
		}*/

		$validator = Validator::make(Input::all(),$rules);
		if($validator->fails()){
			return "error";
		}else{
			
			//if(empty($user->name)){
				$user->name = $author;
				$user->save();
			//}
			
			$chat = new Chat();
			$chat->message = $message;
			$chat->user_id = $user->id;
			$chat->save();	
			return "success";
		}
	}

	public function getDelete($id) {

		$post = Post::find($id);
		$post->delete();

	}

	public function getUpdate($id) {

		$post = Post::find($id);
		$post->name = "hal";
		$post->save();

	}

}