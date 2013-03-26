<?php namespace app\models\person;

class PersonCollection extends \core\model\orm\Collection {
	
	function __construct(array $raw = null, \core\model\orm\Mapper $mapper = null) {
		parent::__construct($raw, $mapper);
	}

	protected function getMyClass() {
		return __CLASS__;
	}
	
	protected function getTargetClass() {
		return 'PersonObject';
	}
	
}