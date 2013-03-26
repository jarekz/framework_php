<?php namespace core\exception;

class FileNotExistsException extends \Exception {
	
	function __construct($message = null, $code = 0) {
		parent::__construct($message, $code);
	}
}