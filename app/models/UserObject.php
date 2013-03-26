<?php namespace app\models;

class UserObject extends \core\auth\User {
	
	private $name;

	function getName() { return $this->fName; }
	function setName($val) { $this->name = $val; }
	function updateName($val) {
		if($val !== $this->name) {
			$this->name = $val;
			$this->beenUpdate();
		}
	}
	
	function __toString() {
		return sprintf('User Object ID:%s, %s', $this->getId(), $this->name);
	}
}