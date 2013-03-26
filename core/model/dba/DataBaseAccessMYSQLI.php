<?php namespace core\model\dba;

class DataBaseAccessMYSQLI extends DataBaseAccess {
	
	private $dbh;
	private $stmt;
	
	function __construct() {
		mysqli_report(MYSQLI_REPORT_STRICT);
	}
	
	function __destruct() {
		$this->statementClose();
		if($this->dbh)
			$this->dbh->close();
	}
	
	private function connect() {
		if(is_null($this->dbh)) {
			extract(\core\Config::get('dbAccess'));
			$this->dbh = new \mysqli($host, $user, $pass, $db);
		}
	}

	protected function doExecute($sqlQuery, $values = null) {
		try {
			$this->connect();
			$this->statementClose();
			
			preg_match_all('/:(\w+)/', $sqlQuery, $matches);
			$queryFields = $matches[1];
			$sqlQuery = preg_replace('/:\w+/', '?', $sqlQuery);
			$this->stmt = $this->dbh->prepare($sqlQuery);
			
			if(!$this->stmt)
				throw new \core\exception\DataBaseException( __METHOD__ . ' ' . "Prepare failed: (" . $this->dbh->errno . ") " . $this->dbh->error);

			if(!$this->bindParams($values, $queryFields))
				throw new \core\exception\DataBaseException( __METHOD__ . ' ' . "Binding parameters failed: (" . $this->dbh->errno . ") " . $this->dbh->error . " types: " . $types);
			
			if(!$this->stmt->execute())
				throw new \core\exception\DataBaseException( __METHOD__ . ' ' . "Execute failed: (" . $this->dbh->errno . ") " . $this->dbh->error);
			
			$this->stmt->store_result();
			
			$lastId = $this->dbh->insert_id;
			if($lastId == 0) $lastId = NULL; 
			$this->setLastInsertId($lastId);
			
			$lastRowCount = $this->stmt->affected_rows;
			if($lastRowCount < 0) $lastRowCount = 0;
			$this->setLastRowCount($lastRowCount);
			
		} catch(\mysqli_sql_exception $err) {
			throw new \core\exception\DataBaseException( __METHOD__ . ' ' . $err->getMessage());
		}
	}
	
	protected function doResult() {		
		$fetchResult = $this->stmt->fetch();
		
		if($fetchResult === FALSE) {
			$this->statementClose();
			throw new \core\exception\DataBaseException( __METHOD__ . " Getting result set failed: (" . $this->dbh->errno . ") " . $this->dbh->error);
		} else if(is_null($fetchResult)) {
			$this->setResult(array());
		} else {
			$this->setResult($this->fetchResult());
		}
		
		$this->statementClose();
	}
	
	private function fetchResult() {
		//bind params
		$columnsName = array();
		$comNamePref = 'col_';
		$metadata = $this->stmt->result_metadata();
		$fields = $metadata->fetch_fields(); 
		
		foreach($fields as $field) {
			${$comNamePref.$field->name} = NULL;
			$columnsName[$field->name] = &${$comNamePref.$field->name};
		}
		
		$metadata->free();
		call_user_func_array(array($this->stmt, 'bind_result'), $columnsName);

		//read data
		$res = array();
		$this->stmt->data_seek(0);

		while ($this->stmt->fetch()) {
			$row = array();
			foreach($columnsName as $colName=>$colVal)
				$row[$colName] = $colVal;
			
			$res[] = $row;
		}
		
		return $res;
	}

	private function bindParams($values, $queryFields) {
		if(is_array($values) && is_array($queryFields)) {
			
			$bind_params = array();
			foreach($queryFields as $field)
				$bind_params[] = &$values[$field];
			
			$types = $this->getParamsTypes($bind_params);
			array_unshift($bind_params, $types);

			if(!call_user_func_array(array($this->stmt,'bind_param'), $bind_params))	
				return false;
		}
		
		return true;
	}


	private function statementClose() {
		if($this->stmt) {
			$this->stmt->free_result();
			$this->stmt->close();
			$this->stmt = null;
		}
	}

	private function getParamsTypes(array $params) {
		$res = '';
		
		foreach($params as $val) {
			switch (gettype($val)) {
				case 'integer':
					$res .= 'i';
					break;

				case 'double':
				case 'float':
					$res .= 'd';
					break;
				
				case 'string':
					$res .= 's';
					break;
				
				default:
					$res .= '-undefined-';
					break;
			}
		}
		
		return $res;
	}
}