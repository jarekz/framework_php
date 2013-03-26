<?php namespace core\controller;

abstract class BaseCrudController extends BaseController {

	const RECORD_ADD		= 'success add record';
	const RECORD_DEL		= 'success delete record';
	const RECORD_UPD		= 'success update record';
	const RECORD_NO_MODIFY	= 'no modify record';
	const RECORD_READ		= 'success read record';
	const RECORD_EMPTY		= 'no record found';
	const WRONG_ID			= 'wrong id';
	const WRONG_PARAM		= 'wrong parameters';
	const DB_ERROR			= 'database error';
	
	protected abstract function createGetParam();
	protected abstract function createCheckParam(array $params);
	protected abstract function createExecute(array $params);
	protected abstract function createParamFailed(array $params);
	
	protected abstract function readGetParam();
	protected abstract function readExecute(array $params);
	protected abstract function readNoRecord();
	
	protected abstract function updateGetParam(); 
	protected abstract function updateCheckParam(array $params);
	protected abstract function updateExecute(array $params);
	protected abstract function updateParamFailed(array $params);
	
	protected abstract function deleteGetParam();
	protected abstract function deleteExecute(array $params);
	
	protected abstract function checkId(array $params);
	protected abstract function idFailed(\core\exception\InvalidIdException $err);
	protected abstract function dbError(\core\exception\DataBaseException $err);

	public function create() {
		try {
			$params = $this->createGetParam();
			
			if(!$this->createCheckParam($params)) 
				throw new \core\exception\InvalidParamException;
			
			$this->createExecute($params);	
			
		} catch(\core\exception\InvalidParamException $err) {
			$this->createParamFailed($params);
			
		} catch(\core\exception\DataBaseException $err) {
			$this->dbError($err);
		}
	}
	
	public function read() {
		try {
			$params = $this->readGetParam();
			$this->readExecute($params);
			
		} catch(\core\exception\NoRecordException $err) {
			$this->readNoRecord();
			
		} catch(\core\exception\DataBaseException $err) {
			$this->dbError($err);
		}
	}
	
	public function update() {
		try {
			$params = $this->updateGetParam();
			
			if(!$this->checkId($params))
				throw new \core\exception\InvalidIdException;
			
			if(!$this->updateCheckParam($params))
				throw new \core\exception\InvalidParamException;
			
			$this->updateExecute($params);
			
		} catch(\core\exception\InvalidIdException $err) {
			$this->idFailed($err);
			
		} catch(\core\exception\InvalidParamException $err) {
			$this->updateParamFailed($params);
			
		} catch(\core\exception\DataBaseException $err) {
			$this->dbError($err);
		}
	}
	
	public function delete() {
		try {
			$params = $this->deleteGetParam();
			
			if(!$this->checkId($params)) 
				throw new \core\exception\InvalidIdException;
			
			$this->deleteExecute($params);
			
		} catch(\core\exception\InvalidIdException $err) {
			$this->idFailed($err);
			
		} catch(\core\exception\DataBaseException $err) {
			$this->dbError($err);
		}
	}
	
}