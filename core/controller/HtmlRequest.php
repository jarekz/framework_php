<?php namespace core\controller;

class HtmlRequest extends BaseRequest {
	
	function __construct() {
		parent::__construct();
	}

	protected function ini() {
		if(count($_GET) > 0) {
			$this->controllerName = $_GET['controller'];
			$this->actionName = $_GET['action'];
			unset($_GET['controller'], $_GET['action']);
			$this->params = array_values($_GET);
		} else {
			if($router = \core\registry\RequestRegistry::getRouter()) {
				$urlParts = explode('/', $router->getInternalUrl($this->geExternaltUrl()));
			} else {
				$urlParts = explode('/', $this->geExternaltUrl());
			}
			$this->controllerName = array_shift($urlParts);
			$this->actionName = array_shift($urlParts);
			$this->params = $urlParts;
			if(count($_POST) > 0) 
				$this->params = array_merge($this->params, $_POST);
		}
		
	}
	
	function gender() {
		return parent::HTML;
	}
	
	function getContentType() {
		return 'text/html';
	}
	
	function buildUrl(array $urlOptions, $routeName = '') {
		if(!empty($routeName) && $router = \core\registry\RequestRegistry::getRouter()) {
			return sprintf('%s%s', $this->getRelativePath(), $router->getExternalUrl($urlOptions, $routeName)); 
		} else {		
			return sprintf('%s/%s', $this->getRelativePath(), implode('/', $urlOptions)); 
		}
	}

	function redirect($uri) {
		header('Location: ' . $this->getAbsolutePath() . $uri);
		die;
	}
	
	function errorNotFound($warning = null) {
		\core\registry\RequestRegistry::getAppController()->dispatchError('404');
		exit();
	}
	
	function errorUnauthorized($warning = null) {
		\core\registry\RequestRegistry::getAppController()->dispatchError('401');
		exit();
	}
}