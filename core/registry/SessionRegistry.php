<?php namespace core\registry;

class SessionRegistry {
	
	private static $instance;
	
	private $isIni = false;

	private function __construct() {}
	
	static function getInstance() {
		if(!self::$instance) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
	
	public function ini() {
		if(!$this->isIni) {
			session_start();
			$this->isIni = TRUE;
		}
	}
	
	public function setVars($key, $var) {
		if(!isset($_SESSION['__vars']))
			$_SESSION['__vars'] = array();
		
		$_SESSION['__vars'][$key] = $var;
	}

	public function getVars($key) {
		if(isset($_SESSION['__vars'][$key])) {
			return $_SESSION['__vars'][$key];
		}
		return NULL;
	}
	
	public function setFlashVars($key, $var, $overwrite) {
		if(!isset($_SESSION['__flashVars']))
			$_SESSION['__flashVars'] = array();
		
		if($overwrite) {
			$_SESSION['__flashVars'][$key] = $var;
		} else {
			if(!isset($_SESSION['__flashVars'][$key]))
				$_SESSION['__flashVars'][$key] = $var;
		}
	}
	
	public function getFlashVars($key) {
		if(isset($_SESSION['__flashVars'][$key])) {
			$var = $_SESSION['__flashVars'][$key];
			unset($_SESSION['__flashVars'][$key]);
			return $var;
		}
		
		return NULL;
	}
	
	public function clearFlashVars() {
		unset($_SESSION['__flashVars']);
	}
	
	public function getUser() {
		if(isset($_SESSION['__user'])) {
			return $_SESSION['__user'];
		}
		
		return NULL;
	}
	
	public function setUser($user) {
		$_SESSION['__user'] = $user;
	}
}