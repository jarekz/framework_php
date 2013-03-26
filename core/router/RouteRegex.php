<?php namespace core\router;

class RouteRegex implements Route {
	
	private $extUrlRegex;
	private $extUrlFormat;
	private $regexParam;
	public $param;

	public function __construct($extUrlRegex, $extUrlFormat) {
		$this->extUrlRegex = $extUrlRegex;
		$this->extUrlFormat = $extUrlFormat;
		$this->param = array();
	}
	
	public function getGender() {
		return 'dynamicRoute';
	}
	
	public function match($extUrl) {
		return $this->parse($extUrl);
	}
	
	public function getInternalUrl($extUrl) {
		if(!$this->regexParam)
			parse($extUrl);
		
		return implode('/', 
			array_merge(
				array($this->controller, $this->action),
				$this->param,
				$this->regexParam
			));
	}
	
	public function getExternalUrl($urlOptions) {
		return @vsprintf($this->extUrlFormat, $urlOptions);
	}

	private function parse($extUrl) {
		$this->regexParam = array();
		$regex = "/$this->extUrlRegex/";
		$res = preg_match($regex, $extUrl, $matches);
		$this->regexParam = array_splice($matches, 1);
		return ($res == 1);
	}

}