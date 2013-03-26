<?php namespace core;

class Config {

	private static $settings = array(
		'debug' => FALSE,
		'mainPath' => './',
		'namespaceControllers' => 'app\controllers\\',
		'defaultController' => '',
		'defaultAction' => '',
		'errorController' => 'core\controller\ErrorController',
		'routerEnable' => FALSE,
		'routerIniFile' => '/app/ini/routes.ini',
		'authEnable' => FALSE,
		'authUserClass' => 'core\auth\User',
		'authAccessLevelsClass' => 'core\auth\DefaultAccessLevels',
		'dbExt' => \core\model\dba\DataBaseAccessFactory::PDO,
		'dbAccess' => array('type' => '', 'host' => '', 'db' => '', 'user' => '', 'pass' => ''),
		'viewDir' => 'app/views/',
		'viewExt' => '.html',
		'viewLayout' => 'layout' 
	);
		
	public static function setSeveral(array $settings) {
		foreach ($settings as $name => $val) {
			if(isset(self::$settings[$name])) {
				self::$settings[$name] = $val;
			}
		}
	}
	
	public static function set($name, $val) {
		if(isset(self::$settings[$name])) {
			self::$settings[$name] = $val;
			return TRUE;
		}
		
		return FALSE;
	}
	
	public static function get($name) {
		if(isset(self::$settings[$name])) {
			return self::$settings[$name];
		}
		
		return NULL;
	}
}