<?php namespace core\auth;

class DefaultAccessLevels extends AccessLevels {
	
	static $GUEST = 0;
	static $REGIS = 1;
	static $ADMIN = 2;
	
	function __construct() {
		$defaultRequiredUserLevel = self::$ADMIN;
		$actionsRequiredUserLevel = array();
		parent::__construct($defaultRequiredUserLevel, $actionsRequiredUserLevel);
	}
}