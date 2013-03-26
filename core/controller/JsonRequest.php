<?php namespace core\controller;

class JsonRequest extends BaseRequest {

	function __construct() {
		parent::__construct();
	}

	protected function ini() {
		$this->handleAction($_SERVER['REQUEST_METHOD']);
		$urlParts = explode('/', $this->geExternaltUrl());
		$this->controllerName = array_shift($urlParts);
		$this->params = $this->decodeParam();
	}
	
	private function handleAction($requestMethod) {
		switch ($requestMethod) {
			case 'PUT':
				$this->actionName = 'create';
				break;
			
			case 'GET':
				$this->actionName = 'read';
				break;
			
			case 'POST':
				$this->actionName = 'update';
				break;
			
			case 'DELETE':
				$this->actionName = 'delete';
				break;

			default:
				header('Allow: PUT, GET, POST, DELETE');
				$this->errorMethodNotAllowed();
		}
	}
	
	private function decodeParam() {
		$param = json_decode(file_get_contents('php://input'), TRUE);
		
		if(!$param)
			$param = array();
		
		return $param;
	}

	function gender() {
		return parent::JSON;
	}
	
	function getContentType() {
		return 'application/json';
	}
}