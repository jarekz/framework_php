<?php namespace core;

class FrontController {
	
	const HTTP		= 'http';
	const JSON		= 'json';
	const CLI		= 'cli';
	
	private static $instance;
	
	private $isIni = FALSE;
	
	static function getInstance() {
		if(!self::$instance)
			self::$instance = new self;
		
		return self::$instance;
	}
	
	private function __construct() {}
	
	function go() {
		if(!$this->isIni) {
			$this->isIni = true;
			$this->createRequestObject();
			\core\registry\SessionRegistry::getInstance()->ini();
			$this->createAppController();
			$this->dispatch();
			\core\registry\SessionRegistry::getInstance()->clearFlashVars();
		}
	}
	
	private function dispatch() {
		$request = \core\registry\RequestRegistry::getRequest();
		$controllerName = $request->getControllerName();
		$actionName = $request->getActionName();
		$controllerName = (empty($controllerName)) ? \core\Config::get('defaultController') : $controllerName;
		$actionName = (empty($actionName)) ? \core\Config::get('defaultAction') : $actionName;
		
		\core\registry\RequestRegistry::getAppController()->dispatch($controllerName, $actionName);
	}

	private function createRequestObject() {
		switch ($this->detectRequestClient()) {
			
			case self::JSON:
				\core\registry\RequestRegistry::setRequest(new \core\controller\JsonRequest());
				break;	
			
			case self::CLI:
				\core\registry\RequestRegistry::setRequest(new \core\controller\CliRequest());
				break;	
			
			default:
				if(\core\Config::get('routerEnable'))
					\core\registry\RequestRegistry::setRouter(new \core\router\Router());
				\core\registry\RequestRegistry::setRequest(new \core\controller\HtmlRequest());
		}
	}
	
	private function detectRequestClient() {

		if(PHP_SAPI == 'cli') {
			return self::CLI;
		}
		
		if(isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
			return self::JSON;
		}
		
		return self::HTTP;
	}
	
	private function createAppController() {
		
		if(\core\Config::get('authEnable')) {
			$appController = new \core\controller\AuthController();
		} else {
			$appController = new \core\controller\AppController();
		}
		
		\core\registry\RequestRegistry::setAppController($appController);
	}
}