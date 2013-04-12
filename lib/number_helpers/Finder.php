<?php namespace lib\number_helpers;

class Finder {
	
	/**
	 * zwraca liczbę liczb w tablicy $arr mniejszych od $val
	 * 
	 * @param   array $arr posortowana rosnąco tablica liczb 
	 * @param   number $val graniczna wartość 
	 * @return  number liczba liczb
    */
	public static function countLessElements(array $arr, $val) {

		if(empty($arr)) return 0;

		$lowerLimit = 0;
		$upperLimit = count($arr) - 1;

		if($arr[$lowerLimit] >= $val) return 0;
		if($arr[$upperLimit] < $val) return $upperLimit+1;

		while ($upperLimit - $lowerLimit > 1) {
			$currentKey = $lowerLimit + floor(($upperLimit - $lowerLimit)/2);
			$currentVal = $arr[$currentKey];

			if($val < $currentVal)
				$upperLimit = $currentKey;
			else if ($val > $currentVal) 
				$lowerLimit = $currentKey;
			else
				return $currentKey;
		}

		return $lowerLimit+1;
	}
}