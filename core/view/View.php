<?php namespace core\view;

class View {
	
	private $templateData;
	private $viewsData;
	private $script;
	private $link;
	
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
		return sprintf('%s%s',$request->getRelativePath(), ltrim($end, '/'));
	}
	
	function buildUrl(array $urlOptions, $routeName = '') {
		return \core\registry\RequestRegistry::getRequest()->buildUrl($urlOptions, $routeName);
	}
	
	/**
	 * wyswietla znaczniki script
	 * 
	 * @return  string ciag znakow do umieszczenia w html
    */
	function printScript() {
		$r = '';
		if(!is_null($this->script))
			foreach($this->script as $script)
				if(isset($script['body'])) {
					$r .= sprintf('<script type="%s">%s</script>', $script['type'], $script['body']);
				} else {
					$r .= sprintf('<script type="%s" src="%s"></script>', $script['type'], $this->baseUrl($script['src']));
				}
		return $r;
	}
	
	/**
	 * dodaje znaczniki script
	 * 
	 * @param   string $scr sciezka wzgledna do pliku
	 * @param   string $type jezyk
	 * @return  NULL
    */
	public function addScriptExt($src, $type = 'text/javascript') {
		if(is_null($this->script)) 
			$this->script = array(); 
		
		if(is_array($src)) {
			foreach($src as $s)
				$this->script[] = array('src' => $s, 'type' => $type);
		} else {
			$this->script[] = array('src' => $s, 'type' => $type);
		}
	}
	
	/**
	 * dodaje znaczniki script
	 * 
	 * @param   string $body kod
	 * @param   string $type jezyk skryptu
	 * @return  NULL
    */
	public function addScriptEmbed($body, $type = 'text/javascript') {
		if(is_null($this->script)) 
			$this->script = array(); 
		
		if(is_array($body)) {
			foreach($body as $b)
				$this->script[] = array('body' => $b, 'type' => $type);
		} else {
			$this->script[] = array('body' => $b, 'type' => $type);
		}
	}
	
	/**
	 * wyswietla znaczniki link
	 * 
	 * @return  string ciag znakow do umieszczenia w html
    */
	function printLink() {
		$r = '';
		if(!is_null($this->link))
			foreach($this->link as $link)
				$r .= sprintf('<link rel="%s" type="%s" media="%s" href="%s" />', $link['rel'], $link['type'], $link['media'], $link['href']);
		return $r;
	}
	
	/**
	 * dodaje znaczniki link
	 * 
	 * @param   string $href sciezka wzgledna do pliku
	 * @param   string $rel
	 * @param   string $type
	 * @param   string $media
	 * @return  NULL
    */
	public function addLink($href, $rel = 'stylesheet', $type = 'text/css', $media = 'all') {
		if(is_null($this->link)) 
			$this->link = array(); 
		
		if(is_array($href)) {
			foreach($href as $h)
				$this->link[] = array('href' => $this->baseUrl($h), 'rel' => $rel, 'type' => $type, 'media' => $media);
		} else {
			$this->link[] = array('href' => $this->baseUrl($href), 'rel' => $rel, 'type' => $type, 'media' => $media);
		}
	}
}