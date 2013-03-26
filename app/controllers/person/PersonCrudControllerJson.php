<?php namespace app\controllers\person;

class PersonCrudControllerJson extends PersonCrudController {
	
	public function __construct() {
		parent::__construct();
	}
	
	protected function idFailed(\core\exception\InvalidIdException $err) {
		$info = self::WRONG_ID;
		$this->getRequest()->errorBadRequest($info);
	}
	
	protected function dbError(\core\exception\DataBaseException $err) {
		$info = self::DB_ERROR . (\core\Config::get('debug') ? ' '.$err->getMessage() : '');
		$this->getRequest()->errorInternalServer($info);
	}
	
	/***CREATE***/
	protected function createExecute(array $params) {
		parent::createExecute($params);
		$this->getRequest()->successCreated();
	}

	protected function createParamFailed(array $params) {
		$info = self::WRONG_PARAM;
		$this->getRequest()->errorBadRequest($info);
	}
	
	/***READ***/
	protected function readExecute(array $params) {
		$result = json_encode(parent::readExecute($params));
		$this->getRequest()->sendResponse($result);
	}
	
	protected function readHelper(&$result, \app\models\person\PersonObject $personObject) {
		if(!is_array($result)) {
			$result = array();
		}
		
		$result[$personObject->getId()] = array('fName' => $personObject->getFirstName(), 'lName' => $personObject->getLastName());
	}
	
	protected function readNoRecord() {
		$info = self::RECORD_EMPTY;
		$this->getRequest()->successNoContent($info);
	}
	
	/***UPDATE***/
	protected function updateExecute(array $params) {
		if(parent::updateExecute($params)) {
			$info = self::RECORD_UPD;
		} else {
			$info = self::RECORD_NO_MODIFY;
		}
		
		$this->getRequest()->successOk($info);
	}
	
	protected function updateParamFailed(array $params) {
		$info = self::WRONG_PARAM;
		$this->getRequest()->errorBadRequest($info);
	}
	
	/***DELETE***/
	protected function deleteExecute(array $params) {
		parent::deleteExecute($params);
		$this->getRequest()->successOk();
	}
}