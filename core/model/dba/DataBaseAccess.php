<?php namespace core\model\dba;

abstract class DataBaseAccess {
	
	private $lastInsertId;
	private $lastRowCount;
	private $result;
	
	abstract protected function doExecute($sqlQuery, $values = null);
	abstract protected function doResult();
	
	function execute($sqlQuery, $values = null) {
		$this->doExecute($sqlQuery, $values);
	}
	
	protected function setLastInsertId($id) {
		$this->lastInsertId = $id;
	}

	function getLastInsertId() {
		return $this->lastInsertId;
	}
	
	protected function setLastRowCount($lastRowCount) {
		$this->lastRowCount = $lastRowCount;
	}

	function getLastRowCount() {
		return $this->lastRowCount;
	}
	
	protected function setResult($result) {
		$this->result = $result;
	}
	
	protected function isResult() {
		return (!is_null($this->result));
	}

	function result($cacheResult = false) {
		$this->doResult();
		return $this->result;
	}
}