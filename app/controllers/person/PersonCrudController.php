<?php namespace app\controllers\person;

use lib\verifiers\ParamsCleaner as ParamsCleaner;

abstract class PersonCrudController extends \core\controller\BaseCrudController {
	
	protected $mapper;

	function __construct() {
		$this->mapper = \core\model\orm\HelperFactory::getMapper('app\models\person\Person');
	}

	protected function findDO($id = NULL) {
		$personObject = (is_null($id)) ? $this->mapper->findAll() : $this->mapper->find($id);
		return $personObject;
	}

	protected function checkId(array $params) {
		if(ParamsCleaner::isNull($params['id'])) return FALSE;
		$personObject = $this->findDO($params['id']);
		return (($personObject) ? TRUE : FALSE);
	}
	
	/***CREATE***/
	protected function createGetParam() {
		$params = ParamsCleaner::getSanitizeParamWithKey($this->getRequest(), 
			'fName', ParamsCleaner::STRING_TRIM, 
			'lName', ParamsCleaner::STRING_TRIM, 
			'fSend', ParamsCleaner::INTEGER);
		
		return $params;
	}
	
	protected function createCheckParam(array $params) {
		return ParamsCleaner::isAllNotNull($params['fName'], $params['lName']);
	}

	protected function createExecute(array $params) {
		$personObject = new \app\models\person\PersonObject();
		$personObject->setFirstName($params['fName']);
		$personObject->setLastName($params['lName']);
		$this->mapper->insert($personObject);
	}
	
	/***READ***/
	protected function readGetParam() {
		$params = ParamsCleaner::getSanitizeParamWithKey($this->getRequest(), 
			'id', ParamsCleaner::INTEGER);
		
		return $params;
	}
	
	protected function readExecute(array $params) {
		$personData = false;

		if(ParamsCleaner::isNotNull($params['id'])) {
			$personData = $this->findDO($params['id']);
		} else {
			$personData = $this->findDO();
		}
		
		if(!$personData) throw new \core\exception\NoRecordException;
		
		$result = '';
		
		if($personData instanceof \app\models\person\PersonCollection) {
			foreach($personData as $personObj)
				$this->readHelper($result, $personObj);
		} else {
			$personObj = $personData;
			$this->readHelper($result, $personObj);
		}
		
		return $result;
	}
	
	protected abstract function readHelper(&$result, \app\models\person\PersonObject $personObject);
	
	/***UPDATE***/
	protected function updateGetParam() {
		$params = ParamsCleaner::getSanitizeParamWithKey($this->getRequest(), 
			'id', ParamsCleaner::INTEGER, 
			'fName', ParamsCleaner::STRING_TRIM, 
			'lName', ParamsCleaner::STRING_TRIM, 
			'fSend', ParamsCleaner::INTEGER);
		
		return $params;
	}
	
	protected function updateCheckParam(array $params) {
		return ParamsCleaner::isAllNotNull($params['fName'], $params['lName']);
	}
	
	protected function updateExecute(array $params) {
		$personObject = $this->findDO($params['id']);
		$personObject->setFirstName($params['fName']);
		$personObject->setLastName($params['lName']);
		return $this->mapper->update($personObject);
	}
	
	/***DELETE***/
	protected function deleteGetParam() {
		$params = ParamsCleaner::getSanitizeParamWithKey($this->getRequest(), 
			'id', ParamsCleaner::INTEGER);
		
		return $params;
	}
	
	protected function deleteExecute(array $params) {
		$personObject = $this->findDO($params['id']);;
		$this->mapper->delete($personObject);
	}
}