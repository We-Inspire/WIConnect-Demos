<?php

use WeInspire\Helpers\WIHelpers;

class DemoController extends BaseController {

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

	public function getIndex()
	{
		return View::make('index');
	}

	public function postRegister() {

		$user = ChatUser::where('laravel_id',Session::getId())->first();
		if($user == null){
			$user = new ChatUser;
			$user->name = "";
			$user->laravel_id = Session::getId();
			$user->color = "hsl(".rand(0,360).", 70%, 75%)";
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

}