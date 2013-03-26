<?php namespace core\model\orm;

abstract class DomainObject {
	
	private $id;
	
	public function __construct($id = null) {
		if(is_null($id))
			$this->markNew();
		else
			$this->id = $id;
	}
	
	static function getCollection($type, array $raw = null, Mapper $mapper = null) {
		return HelperFactory::getCollection($type, $raw, $mapper);
	}
	
	function collection(array $raw = null, Mapper $mapper = null) {
		return self::getCollection(get_class($this), $raw, $mapper);
	}
	
	static function getMapper($type) {
		return HelperFactory::getMapper($type);
	}
	
	function mapper() {
		return self::getMapper(get_class($this));
	}
	
	function markNew() {
		DomainObjectWatcher::addNew($this);
	}
	
	function markDirty() {
		DomainObjectWatcher::addDirty($this);
	}
	
	function markDelete() {
		DomainObjectWatcher::addDelete($this);
	}
	
	function markClean() {
		DomainObjectWatcher::addClean($this);
	}
	
	function setId($id) {
		$this->id = $id;
	}
	
	function getId() {
		return $this->id;
	}
}