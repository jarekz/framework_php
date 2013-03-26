<?php namespace app\auth;

class AppAccessLevels extends \core\auth\AccessLevels {
	
	static $GUEST = 0;
	static $ADMIN = 1;
	
	function __construct() {
		
		$defaultRequiredUserLevel = self::$ADMIN;
		
		$actionsRequiredUserLevel = array(
			'person-create'	=> self::$GUEST,
			'person-read'	=> self::$GUEST,
			'person-update'	=> self::$GUEST,
			'person-delete'	=> self::$GUEST
		);
		
		parent::__construct($defaultRequiredUserLevel, $actionsRequiredUserLevel);
	}
}