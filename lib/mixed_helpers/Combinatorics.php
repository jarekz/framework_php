<?php namespace lib\mixed_helpers;

class Combinatorics {
	
	/**
	 * zwraca tablicę wszystkich możliwych kombinacji argumentów,
	 * powtarzające się argumenty zostają zredukowane
	 * 
	 * @param   string|number $val [, string|number $... ] lista argumentów 
	 * @return  array tablica kominacji lub NULL jeśli brak argumentów
    */
	public static function permutations() {
		$uniqueElements = array_unique(func_get_args());
		if(!empty($uniqueElements)) { 
			$res = array();
			self::_permutationsHelper($res, $uniqueElements, count($uniqueElements));
			return $res;
		} else {
			return NULL;
		}
	}

	private static function _permutationsHelper(&$res, $elements, $elementsNum, $currLevel = 0, $currElement = array()) {
		if($currLevel >= $elementsNum) {
			$res[] = join(array_keys($currElement));
			return;
		}
		foreach($elements as $e) {
			if(!isset($currElement[$e])){
				$t = $currElement;
				$t[$e] = 0;
				self::_permutationsHelper($res, $elements, $elementsNum, $currLevel+1, $t);
			}
		}
	}
}
