<?php

use WeInspire\Helpers\WIHelpers;

class ImageController extends BaseController {

	public $restful = true;

	public function getIndex() {

		return View::make('collage.upload');

	}


	public function getCollage() {

		return View::make('collage.collage');

	}

	public function postRegister() {

		WIHelpers::sendToNode(json_encode(array('nodeid' => Input::get('nodeid'), 'laravelid' => session_id())), "getUserSession");

	}

}