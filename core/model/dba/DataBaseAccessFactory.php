<?php namespace core\model\dba;

class DataBaseAccessFactory {
	
	const FAKE = 'fake';
	const PDO = 'pdo';
	const MYSQLI = 'mysqli';
	
	static private $instance;
	private $globalAccess;
	
	private function __construct() {}
	
	static function getInstance() {
		if(is_null(self::$instance)) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	static function globalAccess() {
		$ins = self::getInstance();
		if(is_null($ins->globalAccess)) {
			$ins->globalAccess = $ins->createDBA(\core\Config::get('dbExt'));
		}
		
		return $ins->globalAccess;
	}
	
	private function createDBA($type) {
		switch ($type) {
			case self::FAKE:
				return new DataBaseAccessFAKE();
				break;
			case self::PDO:
				return new DataBaseAccessPDO();
				break;
			case self::MYSQLI:
				return new DataBaseAccessMYSQLI();
				break;
			default:
				break;
		}
	}
	
}