<?php namespace app\controllers;

class PersonController extends \core\controller\BaseController {
	
	private $crud;

	function __construct() {
		parent::__construct();
	}
	
	private function getCrud() {
		
		if(is_null($this->crud)) {
			switch ($this->getRequest()->gender()) {
				case \core\controller\BaseRequest::CLI:
					$this->crud = new \app\controllers\person\PersonCrudControllerCli();
					break;
				
				case \core\controller\BaseRequest::JSON:
					$this->crud = new \app\controllers\person\PersonCrudControllerJson();
					break;

				default:
					$this->crud = new \app\controllers\person\PersonCrudControllerHtml();
					break;
			}
		}
		
		return $this->crud;
	}

	function actionCreate() {
		return $this->getCrud()->create();
	}

	function actionRead() {
		return $this->getCrud()->read();
	}
	
	function actionUpdate() {
		return $this->getCrud()->update();		
	}

	function actionDelete() {
		return $this->getCrud()->delete();
	}
	
	function actionTestFunctionality() {
		echo 'actionTestFunctionality<br><br><pre>';
		
//		try {
//			\app\lib\PersonPhone::init();
//		} catch (\core\exception\FileNotExistsException $err) {
//			echo $err->getMessage();
//		}
		\core\Config::set('dbExt', \core\model\dba\DataBaseAccessFactory::FAKE);
		
		$mapper = \core\model\orm\HelperFactory::getMapper('app\models\person\Person');
		
		$testData = array(1=>array('fName'=>'pier', 'lName'=>'pierwszy'));
		$testData[] = array('fName'=>'drug', 'lName'=>'drugi');
		
		$dba = \core\model\dba\DataBaseAccessFactory::globalAccess();
		
		$dba->loadData('person', $testData);
		
		
		
//		$personA = new \app\models\PersonObject();
//		$personA->setFirstName('unity');
//		$personA->setLastName('of work');
//		
//		$personA2 = new \app\models\PersonObject();
//		$personA2->setFirstName('unity2');
//		$personA2->setLastName('of work2');
//		
		$personA3 = new \app\models\person\PersonObject();
		$personA3->setFirstName('trz');
		$personA3->setLastName('trzeci');
		
		$personA0 = $mapper->find(1);
		$personA1 = $mapper->find(2);
		$personA1->markDelete();
		
		$personA0->setFirstName('pier-modify');
		
		print_r($testData);
		
		\core\model\orm\DomainObjectWatcher::performOperations();

		var_dump($personA0, $personA1, $personA3);
		print_r($testData);
	}
	
	private function helperTestFunctionality($id) {
	}
}