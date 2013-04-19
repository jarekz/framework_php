<?php namespace core\controller;

abstract class BaseRequest {

	const HTML = 'html';
	const CLI = 'cli';
	const JSON = 'json';

	protected $controllerName, 
			  $actionName, 
			  $params;
	
	function __construct() {
		$this->ini();
	}
	
	protected abstract function ini();
	public abstract function gender();
	public abstract function getContentType();

	function getControllerName() {
		return $this->controllerName;
	}

	function getActionName() {
		return $this->actionName;
	}
	
	function geExternaltUrl() {
		$relativePath = $this->getRelativePath();
		return strtolower(trim(substr($_SERVER['REQUEST_URI'], strlen($relativePath)), '/'));
	}

	/**
	 * zwraca wartość parametru żądania, którego wartość jest ustalana na podstawie klucza
	 * 
	 * klucz może być ciągiem znaków lub indeksem numerycznym
	 * 
	 * przykład użycia:
	 * 
	 * <code>
	 * BaseRequest::getParam('nazwaKlucza');
	 * BaseRequest::getParam(0);
	 * </code> 
	 * 
	 * @param   mixed $key klucz
	 * @return  mixed zwraca wartość parametru jeśli klucz istnieje, NULL w przeciwnym przypadku
    */
	function getParam($key) {
		if(isset($this->params[$key])) {
			return $this->params[$key];
		} else {
			$params = array_values($this->params);
			if(isset($params[$key])) {
				return $params[$key];
			}
		}
		return null;
	}
	
	function setParam($offset, $value) {
		$this->params[$offset] = $value;
	}
	
	function getAbsolutePath() {
		$pathinfo = pathinfo($_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']);
		return 'http://'.$pathinfo['dirname'];
	}
	
	function getRelativePath() {
		$pathinfo = pathinfo($_SERVER['SCRIPT_NAME']);
		return rtrim($pathinfo['dirname'], DIRECTORY_SEPARATOR).'/';
	}
	
	function redirect($uri) {
		throw new \Exception(sprintf("Redirect method not allowed in class [%s]", __CLASS__));
	}
	
	function buildUrl(array $urlOptions, $routeName = '') {
		throw new \Exception(sprintf("BuildUrl method not allowed in class [%s]", __CLASS__));
	}
	
	function sendResponse($body = null) {
		$this->sendHeaders('HTTP/1.1 200 OK');
		if(!is_null($body)) echo $body; 
	}
	
	function successOk($info = null) {
		$this->sendHeaders('HTTP/1.1 200 OK', $info);
		exit();
	}
	
	function successCreated($info = null) {
		$this->sendHeaders('HTTP/1.1 201 Created', $info);
		exit();
	}
	
	function successNoContent($info = null) {
		$this->sendHeaders('HTTP/1.1 204 No Content', $info);
		exit();
	}
	
	function errorBadRequest($info = null) {
		$this->sendHeaders('HTTP/1.1 400 Bad Request', $info);
		exit();
	}
	
	function errorUnauthorized($info = null) {
		$this->sendHeaders('HTTP/1.1 401 Unauthorized', $info);
		exit();
	}
	
	function errorNotFound($info = null) {
		$this->sendHeaders('HTTP/1.1 404 Not Found', $info);
		exit();
	}
	
	function errorMethodNotAllowed($info = null) {
		$this->sendHeaders('HTTP/1.1 405 Method Not Allowed', $info);
		exit();
	}
	
	function errorInternalServer($info = null) {
		$this->sendHeaders('HTTP/1.1 500 Internal Server Error', $info);
		exit();
	}
	
	private function sendHeaders($status, $info = null) {
		header($status);
		header('Content-type: ' . $this->getContentType());
		if(!is_null($info)) header("Warning: $info");
	}
}
