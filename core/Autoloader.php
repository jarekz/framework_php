<?php

class Autoloader {
	
	private static $mainPath = ''; 

	public static function ini($mainPath = null) {
		if(!is_null($mainPath))
			self::$mainPath = $mainPath;
		
		self::autoloadRegister();
	}
	
	public static function load($filePath) {
		$path = str_replace('\\', '/', self::$mainPath.$filePath);
		if(file_exists($path)) {
			require_once $path;
		} else {
			throw new core\exception\FileNotExistsException(sprintf('file name [%s]', $path));
		}
	}
	
	private static function autoloadRegister() {
		spl_autoload_register(array('self', 'autoload'));
	}

	private static function autoload($filePath) {
		//var_dump($filePath);
		self::load($filePath.'.php');
	}
}