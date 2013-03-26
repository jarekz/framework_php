<?php namespace core\controller;

class ErrorController extends \core\controller\BaseController {
	
	function __construct() {
		parent::__construct();
	}

	function action401() {
		echo 'Unauthorized';
	}
	
	function action404() {
		$header = 'HTTP/1.1 404 Not Found';
		header($header);
		echo 'Not Found';
	}
}