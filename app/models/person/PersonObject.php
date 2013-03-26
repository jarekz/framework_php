<?php namespace app\models\person;

class PersonObject extends \core\model\orm\DomainObject {
	
	private $fName,
		    $lName;
	
	function setFirstName($val) {
		if($val !== $this->fName) {
			$this->fName = $val;
			$this->markDirty();
		}
	}
	
	function getFirstName() {
		return $this->fName;
	}
	
	function setLastName($val) {
		if($val !== $this->lName) {
			$this->lName = $val;
			$this->markDirty();
		}
	}
	
	function getLastName() {
		return $this->lName;
	}
	
	function __toString() {
		return sprintf('Person Object ID:%s, %s %s', $this->getId(), $this->fName, $this->lName);
	}
}