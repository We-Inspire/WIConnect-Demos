<?php

class CarouselController extends BaseController {

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
		return View::make('carousel.toft');
	}

	public function postRegister() {

		WIHelpers::sendToNode(json_encode(array('nodeid' => Input::get('nodeid'), 'laravelid' => session_id())), "getUserSession");

	}

}