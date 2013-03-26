<?php namespace core\controller;

class CliRequest extends BaseRequest {

	function __construct() {
		parent::__construct();
	}

	protected function ini() {
		array_shift($_SERVER['argv']);
		$this->restoreSession($_SERVER['argv']);
		$this->controllerName = array_shift($_SERVER['argv']);
		$this->actionName = array_shift($_SERVER['argv']);
		$this->params = $_SERVER['argv'];
	}
	
	function gender() {
		return parent::CLI;
	}
	
	function getContentType() {
		return 'text/plain';
	}
	
	private function restoreSession(&$argv) {
		if(current($argv) == '--sid') {
			array_shift($argv);
			$sid = array_shift($argv);
		}
		
		if(isset($sid))
			session_id ($sid);
	}

	function getAbsolutePath() {
		return NULL;
	}
	
	function getRelativePath() {
		return NULL;
	}
	
	function sendResponse($body = null) {
		parent::sendResponse(sprintf('app [sid:%s] response: %s', session_id(), $body));
	}
	
	function errorNotFound($info = null) {
		echo 'Not Found';
		exit();
	}
	
	function errorUnauthorized($info = null) {
		echo 'Unauthorized';
		exit();
	}
}