<?php namespace core\controller;

class AuthController extends AppController {
	
	private $userLevels;
	private $user;

	function __construct() {
		$this->userLevels = $this->createAccessLevels(\core\Config::get('authAccessLevelsClass'));
	}
	
	function __destruct() {
		\core\registry\SessionRegistry::getInstance()->setUser('User123');
	}
	
	public function dispatch($controller, $action) {
		
		if(is_null($this->user)) {
			$userClass = \core\Config::get('authUserClass');
			if(!$this->recoverUser($userClass)) {
				$this->createUser($userClass);
			}
		}
		
		if(!$this->makeAction($controller, $action)) {
			$this->invokeError();
			return;
		}
		
		if($this->checkPermissionLevel($controller, $action)) {
			$this->invokeAction();
		} else {
			\core\registry\RequestRegistry::getRequest()->errorUnauthorized();
		}
	}
	
	public function userLevels() {
		return $this->userLevels;
	}
	
	private function recoverUser($userClass) {
		$user = \core\registry\SessionRegistry::getInstance()->getUser();
		//var_dump($user);
		return FALSE;
	}
	
	private function createUser($userClass) {
		try {
			$c = new \ReflectionClass($userClass);
			$this->user = $c->newInstance();
		} catch(\Exception $err) {
			throw new \core\exception\AuthException(sprintf('Create user failed, class name [%s]', $userClass), 0);
		}
	}
	
	private function createAccessLevels($accessLevelsClass) {
		try {
			$c = new \ReflectionClass($accessLevelsClass);
			return $c->newInstance();
		} catch(\Exception $err) {
			throw new \core\exception\AuthException(sprintf('Create access levels failed, class name [%s]', $accessLevelsClass), 0);
		}
	}
	
	private function checkPermissionLevel($controller, $action) {
		list($path, $fullName, $name) = $this->controllerPathInfo($controller);
		return $this->userLevels()->checkUserLevel($name, $action, 'GUEST');
	}
}