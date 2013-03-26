<?php namespace app\models\person;

class PersonMapper extends \core\model\orm\Mapper {
	
	function __construct(\core\model\dba\DataBaseAccess $dba) {
		parent::__construct($dba);
	}
		
	function findAll() {
		$sql = "SELECT * FROM person ORDER BY id";
		$this->dba->execute($sql);
		
		if($this->dba->getLastRowCount() > 0) {
			return \core\model\orm\HelperFactory::getCollection('app\models\person\Person', $this->dba->result(), $this);
		} else {
			return false;
		}
	}

	protected function doFindObject($id) {
		$sql = "SELECT * FROM person WHERE id = :id";
		$value = array('id' => $id);
		$this->dba->execute($sql, $value);
		
		if($this->dba->getLastRowCount() === 1) {
			$res = $this->dba->result();
			return $this->createObject($res[0]);
		} else {
			return false;
		}
	}
	
	protected function doCreateObject(array $raw) {
		$obj = new PersonObject($raw['id']);
		$obj->setFirstName($raw['fName']);
		$obj->setLastName($raw['lName']);
		return $obj;
	}
	
	protected function doInsert(\core\model\orm\DomainObject $dmObj) {
		$sql = "INSERT INTO person (fName, lName) VALUES (:fName, :lName)";
		$values = array('fName' => $dmObj->getFirstName(), 'lName' => $dmObj->getLastName());
		$this->dba->execute($sql, $values);
		$dmObj->setId($this->dba->getLastInsertId());
		return ($this->dba->getLastRowCount() === 1);
	}
	
	protected function doUpdate(\core\model\orm\DomainObject $dmObj) {
		$sql = "UPDATE person SET fName=:fName, lName=:lName WHERE id=:id";
		$values = array('id' => $dmObj->getId(), 'fName' => $dmObj->getFirstName(), 'lName' => $dmObj->getLastName());
		$this->dba->execute($sql, $values);
		return ($this->dba->getLastRowCount() === 1);
	}
	
	protected function doDelete(\core\model\orm\DomainObject $dmObj) {
		$sql = "DELETE FROM person WHERE id = :id";
		$values = array('id' => $dmObj->getId());
		$this->dba->execute($sql, $values);
		return ($this->dba->getLastRowCount() === 1);
	}
	
	protected function getTargetClass() {
		return 'PersonObject';
	}
	
}