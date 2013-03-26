<?php namespace app\controllers\person;

use lib\verifiers\ParamsCleaner as ParamsCleaner;

class PersonCrudControllerHtml extends PersonCrudController {
	
	private $view;
	
	public function __construct() {
		parent::__construct();
		$this->view = new \core\view\View();
	}
	
	protected function idFailed(\core\exception\InvalidIdException $err) {
		$this->setFlashBlockOverride('msg', self::WRONG_ID);
		$this->redirect('/person/read');
	}
	
	protected function dbError(\core\exception\DataBaseException $err) {
		$this->setFlashBlockOverride('msg', self::DB_ERROR . (\core\Config::get('debug') ? ' '.$err->getMessage() : ''));
		$this->getRequest()->sendResponse($this->view->render());
	}
	
	/***CREATE***/
	protected function createExecute(array $params) {
		parent::createExecute($params);
		$this->setFlashBlockOverride('msg', self::RECORD_ADD);
		$this->redirect('/person/read');
	}

	protected function createParamFailed(array $params) {
		if(ParamsCleaner::isNotNull($params['fSend'])) $this->setFlashBlockOverride('msg', self::WRONG_PARAM);
		
		$this->view->setViewVar('personForm', 'actionRoute', 'osobadodaj');
		$this->view->setViewVar('personForm', 'fName', $params['fName']);
		$this->view->setViewVar('personForm', 'lName', $params['lName']);
		$this->view->menu = "<br/><a href=\"{$this->getRequest()->buildUrl(array('person', 'read'))}\">BACK</a>";
		$this->view->content = $this->view->getViewAsVar('personForm');
		
		$this->getRequest()->sendResponse($this->view->render());
	}
	
	/***READ***/
	protected function readExecute(array $params) {
		$this->view->content = parent::readExecute($params);
		$this->setFlashBlockOverride('msg', self::RECORD_READ);
		$this->view->menu = "<br/><a href=\"{$this->getRequest()->buildUrl(array(), 'osobadodaj')}\">ADD RECORD</a>";
		$this->getRequest()->sendResponse($this->view->render());
	}
	
	protected function readHelper(&$result, \app\models\person\PersonObject $personObject) {
		$this->view->setViewVar('personListItem', 'id', $personObject->getId());
		$this->view->setViewVar('personListItem', 'fName', $personObject->getFirstName());
		$this->view->setViewVar('personListItem', 'lName', $personObject->getLastName());
		$result .= $this->view->getViewAsVar('personListItem');
	}
	
	protected function readNoRecord() {
		$this->setFlashBlockOverride('msg', self::RECORD_EMPTY);
		$this->view->menu = "<br/><a href=\"{$this->getRequest()->buildUrl(array(), 'osobadodaj')}\">ADD RECORD</a>";
		$this->getRequest()->sendResponse($this->view->render());
	}
	
	/***UPDATE***/
	protected function updateExecute(array $params) {
		if(parent::updateExecute($params)) {
			$this->setFlashBlockOverride('msg', self::RECORD_UPD);
		} else {
			$this->setFlashBlockOverride('msg', self::RECORD_NO_MODIFY);
		}
		$this->redirect('/person/read');
	}
	
	protected function updateParamFailed(array $params) {
		$id = $params['id'];
		
		if(ParamsCleaner::isNull($params['fSend'])) {
			$personObject = $this->findDO($id);
			$fName = $personObject->getFirstName();
			$lName = $personObject->getLastName();
		} else {
			$fName = $params['fName'];
			$lName = $params['lName'];
			$this->setFlashBlockOverride('msg', self::WRONG_PARAM);
		}

		//$this->view->setViewVar('personForm', 'actionRoute', 'osobaedytuj');
		//$this->view->setViewVar('personForm', 'action', $this->getRequest()->getAbsolutePath().'/person/update/'.$id.'/');
		$this->view->setViewVar('personForm', 'action', $this->getRequest()->buildUrl(array('person', 'update', $id)));
		$this->view->setViewVar('personForm', 'fName', $fName);
		$this->view->setViewVar('personForm', 'lName', $lName);
		$this->view->menu = "<br/><a href=\"{$this->getRequest()->buildUrl(array('person', 'read'))}\">BACK</a>";
		$this->view->content = $this->view->getViewAsVar('personForm');
		$this->getRequest()->sendResponse($this->view->render());
	}
	
	/***DELETE***/
	protected function deleteExecute(array $params) {
		parent::deleteExecute($params);
		$this->setFlashBlockOverride('msg', self::RECORD_DEL);
		$this->redirect('/person/read');
	}

}