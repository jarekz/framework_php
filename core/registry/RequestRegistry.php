<?php namespace core\registry;

class RequestRegistry {
	
	private static $request;
	private static $router;
	private static $appController;

	static function setRequest(\core\controller\BaseRequest $request) {
		self::$request = $request;
	}
	
	static function getRequest() {
		return self::$request;
	}
	
	static function setAppController(\core\controller\AppController $appController) {
		self::$appController = $appController;
	}
	
	static function getAppController() {
		return self::$appController;
	}
	
	static function setRouter(\core\router\Router $router) {
		self::$router = $router;
	}
	
	static function getRouter() {
		return self::$router;
	}
}