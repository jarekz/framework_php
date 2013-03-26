<?php namespace lib\verifiers;

class ParamsCleaner {
	
	/**
	 * @const STRING nazwa funkcji sprawdzającej ciągi znaków
	*/
	const STRING = 'String';
	
	/**
	 * @const STRING_TRIM nazwa funkcji przycinającej ciągi znaków przed sprawdzeniem
	*/
	const STRING_TRIM = 'StringTrim';
	
	/**
	 * @const INTEGER nazwa funkcji sprawdzającej liczby całkowite
	*/
	const INTEGER = 'Integer';

	/**
	 * zwraca tablicę parametrów z kluczami
	 * 
	 * przykład użycia:
	 * 
	 * <code>
	 * ParamsCleaner::getSanitizeParamWithKey($request,
	 * 'fName', ParamsCleaner::STRING_TRIM, 
	 * 'lName', ParamsCleaner::STRING_TRIM,
	 * 'fSend', ParamsCleaner::INTEGER);
	 * </code> 
	 * 
	 * @static
	 * @param   BaseRequest	$request obiekt aktualnego żądania
	 * @param   string $key klucz paramentru
	 * @param   string $type funkcja sprawdzająca typ parametru
	 * @param   string $key Opcjonalnie kolejny klucz...
	 * @param   string $type Opcjonalnie kolejna funkcja...
	 * @return  array tablica parametrów
    */
	static function getSanitizeParamWithKey() {
		$arg = func_get_args();
		$sanitizeParams = array();
		$i = 0;
		$request = array_shift($arg);
		reset($arg);
		
		while ($key = current($arg)) {
			$param = $request->getParam($key); //find param by key
			if(is_null($param)) {
				$param = $request->getParam($i); //find param by index
			}
			
			$type = next($arg);
			$func = 'validate'.$type;
			
			if(is_callable(__CLASS__.'::'.$func)) {
				$sanitizeParams[$key] = call_user_func(array(__CLASS__, $func), $param);
			} else {
				$sanitizeParams[$key] = $param;
			}
			
			next($arg);
			$i++;
		}
		
		return $sanitizeParams;
	}
	
	/**
	 * sprawdza czy wartość zmiennej jest poprawnym ciągiem znaków,
	 * ciąg jest poprawny gdy:
	 * - nie jest NULL lub FALSE
	 * - długość ciągu jest większa od 0
	 * 
	 * wartość zmiennej jest zawsze rzutowana na typ 'string'
	 * 
	 * dodatkowo przed sprawdzeniem obcina białe znaki na początku i na końcu
	 * 
	 * @static
	 * @param mixed zmienna do sprawdzenia
	 * @return string ciąg znaków, jeśli zmienna wejściowa nie jest poprawny zwraca NULL
    */
	static function validateStringTrim($var) {
		$var = trim($var);
		return self::validateString($var);
	}
	
	/**
	 * sprawdza czy wartość zmiennej jest poprawnym ciągiem znaków,
	 * ciąg jest poprawny gdy:
	 * - nie jest NULL lub FALSE
	 * - długość ciągu jest większa od 0
	 * 
	 * wartość zmiennej jest zawsze rzutowana na typ 'string'
	 * 
	 * @static
	 * @param mixed zmienna do sprawdzenia
	 * @return string ciąg znaków, jeśli zmienna wejściowa nie jest poprawny zwraca NULL
    */
	static function validateString($var) {
		if(isset($var) && strlen($var)>0) {
			settype($var, 'string');
			return $var;
		}
		else {
			return NULL;
		}
	}
	
	/**
	 * sprawdza czy zmienna wejściowa jest poprawną liczbą całkowitą,
	 * wartość jest poprawna gdy:
	 * - nie jest NULL lub FALSE
	 * 
	 * zmienna wejściowa jest zawsze rzutowana na typ 'integer'
	 * 
	 * @static
	 * @param mixed zmienna do sprawdzenia
	 * @return integer wartość wyjściowa, jeśli zmienna wejściowa nie jest poprawna zwraca NULL
    */
	static function validateInteger($var) {
		if(isset($var)) {
			settype($var, 'integer');
			return $var;
		}
		else {
			return NULL;
		}
	}
	
	/**
	 * sprawdza czy wartość zmiennej jest NULL,
	 * 
	 * @static
	 * @param mixed zmienna do sprawdzenia
	 * @return boolean zwraca TRUE gdy wartość zmiennej jest NULL, FALSE w przeciwnym przypadku 
    */
	static function isNull($var) {
		return is_null($var);
	}
	
	/**
	 * sprawdza czy wartość zmiennej nie jest NULL,
	 * 
	 * @static
	 * @param mixed zmienna do sprawdzenia
	 * @return boolean zwraca TRUE gdy wartość zmiennej nie jest NULL, FALSE w przeciwnym przypadku 
    */
	static function isNotNull($var) {
		return !is_null($var);
	}

	/**
	 * sprawdza czy wartości zmiennych są NULL,
	 * 
	 * @static
	 * @param mixed zmienna do sprawdzenia
	 * @param mixed Opcjonalnie kolejna zmienna...
	 * @return boolean zwraca TRUE gdy wartości wszystkich zmiennych są NULL, FALSE w przeciwnym przypadku 
    */
	static function isAllNull() {
		$args = func_get_args();
		
		foreach ($args as $var)
			if(self::isNotNull($var))
				return false;
		
		return true;
	}
	
	/**
	 * sprawdza czy wartości w tablicy są NULL,
	 * 
	 * @static
	 * @param array tablica do sprawdzenia
	 * @return boolean zwraca TRUE gdy wszystkie wartości w tablicy są NULL, FALSE w przeciwnym przypadku 
    */
	static function isAllNullArray(array $params) {
		if(empty($params))
			return true;
		
		foreach ($args as $params)
			if(self::isNotNull($var))
				return false;
		
		return true;
	}
	
	/**
	 * sprawdza czy wartości zmiennych nie są NULL,
	 * 
	 * @static
	 * @param mixed zmienna do sprawdzenia
	 * @param mixed Opcjonalnie kolejna zmienna...
	 * @return boolean zwraca TRUE gdy wartości wszystkich zmiennych nie są NULL, FALSE w przeciwnym przypadku 
    */
	static function isAllNotNull() {
		$args = func_get_args();
		
		foreach ($args as $var)
			if(self::isNull($var))
				return false;
		
		return true;
	}
	
	/**
	 * sprawdza czy wartości w tablicy nie są NULL,
	 * 
	 * @static
	 * @param array tablica do sprawdzenia
	 * @return boolean zwraca TRUE gdy wszystkie wartości w tablicy nie są NULL, FALSE w przeciwnym przypadku 
    */
	static function isAllNotNullArray(array $params) {
		if(empty($params))
			return false;
		
		foreach ($params as $var)
			if(self::isNull($var))
				return false;
		
		return true;
	}
}