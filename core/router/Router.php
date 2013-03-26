<?php namespace core\router;

class Router {
	
	private $staticRoute;
	private $dynamicRoute;
	
	public function __construct() {
		$this->makeRoutes($this->readIni());
	}
	
	private function readIni() {
		$routerIniFile = trim(\core\Config::get('routerIniFile'), '/');
		$iniPath = realpath(dirname(__FILE__).'/../../'.$routerIniFile);
		
		if(FALSE === ($iniData = @parse_ini_file($iniPath, TRUE)))
			throw new \core\exception\RouterException("Routes ini file [$routerIniFile] faild.");
		
		$routesData = array();
		foreach($iniData as $namespace => $properties){
			list($name, $extends) = array_pad(explode(':', $namespace) ,2 ,NULL);
			$name = trim($name);
			$extends = trim($extends);
		
			if(!isset($routesData[$name])) $routesData[$name] = array();
			if(isset($iniData[$extends])){
				foreach($iniData[$extends] as $prop => $val)
					$routesData[$name][$prop] = $val;
			}
		
			// overwrite / set current namespace values
			foreach($properties as $prop => $val)
				$routesData[$name][$prop] = $val;
		}
		
		return $routesData;
	}
	
	private function makeRoutes(array $routesData) {
		$this->staticRoute = new \stdClass();
		$this->dynamicRoute = new \stdClass();
		foreach($routesData as $routeName => $routeData) {
			$route = $this->routeFactory($routeData, $routeName);
			$this->{$route->getGender()}->$routeName = $route;
		}
	}
	
	private function routeFactory(array $routeData, $routeName) {
		switch ($routeData['type']) {
			case 'static':
				$requiredFileds = array('type', 'extUrl', 'controller', 'action');
				$this->checkData($requiredFileds, $routeData, $routeName);
				$route = new RouteStatic($routeData['extUrl']);
				break;
			case 'regex':
				$requiredFileds = array('type', 'extUrlRegex', 'extUrlFormat', 'controller', 'action');
				$this->checkData($requiredFileds, $routeData, $routeName);
				$route = new RouteRegex($routeData['extUrlRegex'], $routeData['extUrlFormat']);
				break;
			default:
				throw new \core\exception\RouterException("Route [$routeName] type not supported.");
				break;
		}
		
		$route->controller = $routeData['controller'];
		$route->action = $routeData['action'];
		if(isset($routeData['param']) && is_array($routeData['param'])) $route->param = $routeData['param'];
		
		return $route;
	}
	
	private function checkData(array $requiredFileds, array $routeData, $routeName) {
		array_walk($requiredFileds, function($field) use ($routeName, $routeData){
			if(!isset($routeData[$field]) || empty($routeData[$field]))
				throw new \core\exception\RouterException("Route [$routeName] data faild.");
		});
	}

	public function getInternalUrl($extUrl) {
		$slash_extUrl = (isset($extUrl[0]) && $extUrl[0] == '/') ? $extUrl : '/'.$extUrl;
		if($route = $this->searchByExtUrl($slash_extUrl)) {
			return $route->getInternalUrl($slash_extUrl);
		}
		return $extUrl;
	}
	
	public function getExternalUrl(array $urlOptions, $routeName) {
		if(empty($routeName)) 
			throw new \core\exception\RouterException("Empty route name.");
		
		if($route = @$this->staticRoute->$routeName)
			return $route->getExternalUrl($urlOptions);
		
		if($route = @$this->dynamicRoute->$routeName)
			return $route->getExternalUrl($urlOptions);
		
		throw new \core\exception\RouterException("Route name [$routeName] faild.");
	}
	
	private function searchByExtUrl($extUrl) {
		foreach($this->staticRoute as $route) {
			if($route->match($extUrl))
				return $route;
		}
		foreach($this->dynamicRoute as $route) {
			if($route->match($extUrl))
				return $route;
		}
		return FALSE;
	}
	
}
