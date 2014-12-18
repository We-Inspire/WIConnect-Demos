<?php
use WeInspire\Helpers\WIHelpers;
class ChatObserver {

	private $data_updated = false;

	public function saving($model) {
	}

	public function created($model) {
		echo "created";

		$data = array();
		$data['type'] = "create";
		$data['model'] = array();
		$data['model']['table'] = $model->getTable();
		$attr = $model->getAttributes();
		$chat = Chat::with('chatUser')->where('id',$attr['id'])->first();
		$data['model']['item'] = $chat->toArray();//$model->getAttributes();

		WIHelpers::sendToNode(WIHelpers::toJSON($data), "getData");
	}

	public function updated($model) {
		echo "updated";

		$data = array();
		$data['type'] = "update";
		$data['model'] = array();
		$data['model']['table'] = $model->getTable();
		$data['model']['item_old'] = $model->getOriginal();
		$data['model']['item'] = $model->getAttributes();

		//return var_dump($data);
		WIHelpers::sendToNode(WIHelpers::toJSON($data), "getData");
	}

	public function deleted($model) {
		
		//return var_dump($model);

		$data = array();
		$data['type'] = "delete";
		$data['model'] = array();
		$data['model']['table'] = $model->getTable();
		$data['model']['item_old'] = $model->getOriginal();
		$data['model']['item'] = $model->getAttributes();

		WIHelpers::sendToNode(WIHelpers::toJSON($data), "getData");

	}

}
?>
