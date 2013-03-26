<?php namespace core\view;

class View {
	
	private $templateData;
	private $viewsData;

	function __construct() {
		$dynamicVar = array();
		$data = array();
	}
	
	function __set($name, $value) {
		$this->templateData[$name] = $value;
	}
	
	function __get($name) {
		if(isset($this->templateData[$name])) 
			return $this->templateData[$name];
		
		return NULL;
	}
	
	function getViewAsVar($viewName) {
		if(isset($this->viewsData[$viewName]))
			extract($this->viewsData[$viewName]);
		
		ob_start();
		include(\core\Config::get('mainPath') . \core\Config::get('viewDir') . $viewName . \core\Config::get('viewExt'));
		return ob_get_clean();
	}
	
	function setViewVar($viewName, $key, $value) {
		if(!isset($this->viewsData[$viewName]))
			$this->viewsData[$viewName] = array();
		
		$this->viewsData[$viewName][$key] = $value;
	}
	
	function render() {
		if(is_array($this->templateData))
			extract($this->templateData);
		
		ob_start();
		include(\core\Config::get('mainPath') . \core\Config::get('viewDir') . \core\Config::get('viewLayout') . \core\Config::get('viewExt'));
		return ob_get_clean();
	}
	
	function baseUrl($end = '') {
		$request = \core\registry\RequestRegistry::getRequest();
		return sprintf('%s/%s',$request->getRelativePath(), ltrim($end, '/'));
	}
	
	function buildUrl(array $urlOptions, $routeName = '') {
		return \core\registry\RequestRegistry::getRequest()->buildUrl($urlOptions, $routeName);
	}
}