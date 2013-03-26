<?php namespace core\model\dba;

class DataBaseAccessPDO extends DataBaseAccess {
	
	private $dbh;
	private $stmt;
	
	function __destruct() {
		$this->dbh = null;
	}
	
	private function connect() {
		if(is_null($this->dbh)) {
			extract(\core\Config::get('dbAccess'));
			$this->dbh = new \PDO("$type:host=$host;dbname=$db;charset=UTF-8", $user, $pass);
			$this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
	}

	protected function doExecute($sqlQuery, $values = null) {
		try {
			$this->connect();
			$stmt = $this->dbh->prepare($sqlQuery);
			
			if(is_array($values))
				$stmt->execute($values);
			else
				$stmt->execute();
			
			$this->stmt = $stmt;
			
			$lastId = $this->dbh->lastInsertId();
			if($lastId == 0) $lastId = NULL; 
			$this->setLastInsertId($lastId);
			$this->setLastRowCount($stmt->rowCount());

		} catch(\PDOException $err) {
			throw new \core\exception\DataBaseException( __METHOD__ . ' ' . $err->getMessage());
		}
	}
	
	protected function doResult() {		
		try {
			$this->setResult($this->stmt->fetchAll(\PDO::FETCH_ASSOC));			
		} catch(\PDOException $err) {
			throw new \core\exception\DataBaseException( __METHOD__ . ' ' . $err->getMessage());
		}
	}
}