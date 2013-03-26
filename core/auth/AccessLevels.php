<?php namespace core\auth;

abstract class AccessLevels {
	
	private $defaultRequiredUserLevel;
	private $actionsRequiredUserLevel;
	
	function __construct($defaultRequiredUserLevel, $actionsRequiredUserLevel) {
		$this->defaultRequiredUserLevel = $defaultRequiredUserLevel;
		$this->actionsRequiredUserLevel = $actionsRequiredUserLevel;
	}
	
	public final function checkUserLevel($controllerName, $actionName, $levelName) {
		$actionRequiredLevel = $this->getActionsRequiredLevel($controllerName, $actionName);
		
		if(!isset(static::${$levelName}))
			throw new \core\exception\AuthException(sprintf('Level name failed [%s]', $levelName), 0);
		
		return (static::${$levelName} >= $actionRequiredLevel);
	}
	
	private function getActionsRequiredLevel($controllerName, $actionName) {
		
		$key = strtolower($controllerName.'-'.$actionName);
		
		if(is_array($this->actionsRequiredUserLevel) && isset($this->actionsRequiredUserLevel[$key])) {
			return $this->actionsRequiredUserLevel[$key];
		}
		
		if(!is_null($this->defaultRequiredUserLevel)) {
			return $this->defaultRequiredUserLevel;
		}
		
		throw new \core\exception\AuthException('Default required user level not set', 0);
	}
}