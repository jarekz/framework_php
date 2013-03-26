<?php namespace core\controller;

class AppController {
	
	private $controller;
	private $action;

	function __construct() {}
	
	function dispatch($controller, $action) {
		
		if($this->makeAction($controller, $action, $useAbsolutePath)) {
			return $this->invokeAction();
		} else {
			$this->invokeError();
		}
	}
	
	function dispatchError($action) {
		$controller = \core\Config::get('errorController');
		
		if($this->makeAction($controller, $action)) {
			return $this->invokeAction();
		}
		
		throw new \core\exception\AppControllerException(sprintf('Error controller failed, controller class [%s], action [%s]', $controller, $action), 0);
	}
	
	protected function makeAction($controller, $action) {
		list($controllerPath) = $this->controllerPathInfo($controller);

		if(empty($controllerPath))
			$controller = \core\Config::get('namespaceControllers').ucfirst($controller.'Controller');
		
		$action = 'action'.ucfirst($action);

		return ($this->searchAction($controller, $action));
	}
	
	protected function controllerPathInfo($controllerPath) {
		preg_match('@^((.*?)\\\?)(([^\\\]*?)(Controller)?)$@', $controllerPath, $m);
		$path = $m[1];
		$fullName = $m[3];
		$name = $m[4];
		return array($path, $fullName, $name);
		
	}

	private function searchAction($controller, $action) {
		try {
			$this->controller = new \ReflectionClass($controller);
		} catch (\core\exception\FileNotExistsException $err) {
			return FALSE;
		}
		
		try {
			$this->action = new \ReflectionMethod($controller, $action);
		} catch(\ReflectionException $err) {
			return FALSE;
		}
		
		return TRUE;
	}

	protected function invokeAction() {
		try {
			return $this->action->invoke($this->controller->newInstance());
		} catch(\ReflectionException $err) {
			return FALSE;
		} 
	}
	
	protected function invokeError() {
		\core\registry\RequestRegistry::getRequest()->errorNotFound();
	}
}