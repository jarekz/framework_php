<?php namespace core\controller;

class BaseController {
	
	function __construct() {
		
	}

	protected function getRequest() {
		return \core\registry\RequestRegistry::getRequest();
	}
	
	protected function redirect($uri) {
		$this->getRequest()->redirect($uri);
	}
	
	protected function forward($controllerName, $actionName) {
		\core\registry\RequestRegistry::getAppController()->dispatch($controllerName, $actionName);
	}
	
	protected function setFlash($key, $var) {
		\core\registry\SessionRegistry::getInstance()->setFlashVars($key, $var, true);
	}
	
	protected function setFlashBlockOverride($key, $var) {
		\core\registry\SessionRegistry::getInstance()->setFlashVars($key, $var, false);
	}
	
	protected function getFlash($key) {
		return \core\registry\SessionRegistry::getInstance()->getFlashVars($key);
	}	
}