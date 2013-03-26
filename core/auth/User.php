<?php namespace core\auth;

class User extends \core\model\orm\DomainObject {
	
	function __construct($id = null) {
		if(!is_null($id))
			$this->setId($id);
	}
	
	protected function beenUpdate() {
		$this->markDirty();
	}
	
}