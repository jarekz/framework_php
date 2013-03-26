<?php namespace core\model\orm;

abstract class Mapper {
	
	protected $dba;
	
	function __construct(\core\model\dba\DataBaseAccess $dba) {
		$this->dba = $dba;
	}
	
	abstract function findAll();
	abstract protected function doFindObject($id);
	abstract protected function doCreateObject(array $raw);
	abstract protected function doInsert(DomainObject $dmObj);
	abstract protected function doUpdate(DomainObject $dmObj);
	abstract protected function doDelete(DomainObject $dmObj);
	abstract protected function getTargetClass();
	
	function find($id) {
		$dmObj = DomainObjectWatcher::getObject($this->getTargetClass(), $id);
		
		if(is_null($dmObj)) {
			$dmObj = $this->doFindObject($id);
			if($dmObj) DomainObjectWatcher::addObject($dmObj);
		}
		
		return $dmObj;
	}
	
	function createObject(array $raw) {
		
		$dmObj = DomainObjectWatcher::getObject($this->getTargetClass(), $raw['id']);
		
		if(is_null($dmObj)) {
			$dmObj = $this->doCreateObject($raw);
			DomainObjectWatcher::addObject($dmObj);
			$dmObj->markClean();
		}
		
		return $dmObj;
	}
	
	function insert(DomainObject $dmObj) {
		DomainObjectWatcher::addObject($dmObj);
		DomainObjectWatcher::addClean($dmObj);
		return $this->doInsert($dmObj);
	}
	
	function update(DomainObject $dmObj) {
		DomainObjectWatcher::addClean($dmObj);
		return $this->doUpdate($dmObj);
	}
	
	function delete(DomainObject $dmObj) {
		DomainObjectWatcher::addClean($dmObj);
		DomainObjectWatcher::removeObject($dmObj);
		return $this->doDelete($dmObj);
	}
}