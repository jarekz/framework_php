<?php namespace lib\number_helpers;

class Numbers {
	
	public static function isPrime($num) {
		if($num === 2) return TRUE;
		if(!($num&1)) return FALSE;

		$halfNum = $num>>1;

		for($i=3; $i<=$halfNum; $i++)
			if(!($num%$i)) return FALSE;

		return TRUE;
	}
}
