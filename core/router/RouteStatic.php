<?php namespace core\router;

class RouteStatic implements Route {
	
	public $extUrl;
	public $controller;
	public $action;
	public $param;

	public function __construct($extUrl) {
		$this->extUrl = $extUrl;
		$this->param = array();
	}
	
	public function getGender() {
		return 'staticRoute';
	}

	public function match($extUrl) {
		return $extUrl == $this->extUrl;
	}
	
	public function getInternalUrl($extUrl) {
		return implode('/', 
			array_merge(
				array($this->controller, $this->action), 
				$this->param
			));
	}
	
	public function getExternalUrl($urlOptions) {
		return $this->extUrl;
	}

	
}