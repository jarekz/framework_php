<?php namespace core\model\orm;

abstract class Collection implements \Iterator {
	
	private $obj = array(),
			$cursor = 0,
			$raw,
			$mapper,
			$total;
	
	function __construct(array $raw = null, Mapper $mapper = null) {
		if(!is_null($raw) && is_null($mapper)) {
			throw new \InvalidArgumentException('Instance Class Mapper is null');
		} else {
			$this->raw = $raw;
			$this->mapper = $mapper;
			$this->total = count($raw);
		}
	}
	
	protected abstract function getMyClass();
	protected abstract function getTargetClass();
	
	function add(DomainObject $obj) {
		$class = $this->getTargetClass();
		if($obj instanceof $class) {
			$this->obj[$this->total] = $obj;
			$this->total++;
		} else {
			throw new \core\exception\InvalidCollectionElementException('attempted add Element:'.get_class($obj).' to Collection:'.$this->getMyClass());
		}	
	}
	
	private function getRow($index) {
		if(isset($this->obj[$index])) {
			return $this->obj[$index];
		}
		
		if(isset($this->raw[$index])) {
			$this->obj[$index] = $this->mapper->createObject($this->raw[$index]);
			return $this->obj[$index];
		}
			
		return NULL;
	} 

	function current() {
		return $this->getRow($this->cursor);
	}
	
	function key() {
		return $this->cursor;
	}

	function next() {
		$row = $this->current();
		$this->cursor++;
		return $row;
	}
	
	function rewind() {
		$this->cursor = 0;
	}
	
	function valid() {
		return (!is_null($this->current()));
	}
}