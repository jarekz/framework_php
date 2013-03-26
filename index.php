<?php
/***CONFIG***/
define('MAIN_PATH', './');

$settings = array(
	'debug'		=> TRUE,
	'namespaceControllers' => 'app\controllers\\',
	'defaultController' => 'Person',
	'defaultAction' => 'read',
	'errorController' => 'app\controllers\ErrorController',
	'routerEnable' => TRUE,
	'routerIniFile' => '/app/ini/routes.ini',
	'authEnable' => TRUE,
	'authUserClass' => 'app\models\UserObject',
	'authAccessLevelsClass' => 'app\auth\AppAccessLevels',
	'dbExt' => 'mysqli', //[pdo, mysqli]
	'dbAccess' => array('type' => 'mysql', 'host' => 'localhost', 'db' => 'mvc', 'user' => 'root', 'pass' => ''),
	'viewDir' => 'app/views/',
	'viewExt' => '.html',
	'viewLayout' => 'layout' 
);
/***CONFIG END***/

error_reporting(E_ALL);

try {
	require_once MAIN_PATH.'core/Autoloader.php';
	Autoloader::ini(MAIN_PATH);
	\core\Config::set('mainPath', MAIN_PATH);
	\core\Config::setSeveral($settings);
	$fc = \core\FrontController::getInstance();
	$fc->go();
}  catch (Exception $err) {
	require_once MAIN_PATH.'core/Logger.php';
	$logger = new core\Logger();
	$logger->setError($err);
	(\core\Config::get('debug')) ? $logger->printError() : $logger->saveError();
	header('HTTP/1.1 500 Internal Server Error');
	exit();
}