<?php namespace lib\string_helpers;

class StringConvert {
	
	private static $_regionalToUniversalLetter = array(
		'ą'=>'a',
		'Ą'=>'A',
		'ć'=>'c',
		'Ć'=>'C',
		'ę'=>'e',
		'Ę'=>'E',
		'ł'=>'l',
		'Ł'=>'L',
		'ń'=>'n',
		'Ń'=>'N',
		'ó'=>'o',
		'Ó'=>'O',
		'ż'=>'z',
		'Ż'=>'Z',
		'ź'=>'z',
		'Ź'=>'Z',
	);
	
	public static function createSlug($str, array $options = array()) {
		$default_opt = array(
			'sep'=>'-',
			'max_words'=>NULL,
			'tolower'=>FALSE,
			'remove_entities'=>TRUE,
			'remove_html_tag'=>TRUE,
		);
		$opt = array_merge($default_opt, $options);
		
		$r = strtr($str, self::$_regionalToUniversalLetter); //remove regional letters
		$r = preg_replace('/('.preg_quote($opt['sep']).')+/', ' ', $r); //covert separator (if exist in oryginal string) to space
		
		$allowChars[] = '[\w ]+';
		
		//remove enitities
		$entities = join(')|(', get_html_translation_table(HTML_ENTITIES));
		$entities = '&[^;]*;';
		if($opt['remove_entities']) {
			$r = preg_replace("/($entities)/", '', $r);
		} else {
			$allowChars[] = $entities;
		}
		
		//remove html tag
		$html_tag = '<[^>]*>';
		if($opt['remove_html_tag']) {
			$r = preg_replace("/($html_tag)/", '', $r);
		} else {
			$allowChars[] = $html_tag;
		}
		
		preg_match_all('/(' . join(')|(', $allowChars) . ')/', $r, $match); //permit allow chars
		$r = join('', $match[0]);
		$r = preg_replace('/[ ]+/', $opt['sep'], $r); //covert space to separator
		$r = trim($r, $opt['sep']);
		
		if(!is_null($opt['max_words'])) {
			$a = explode($opt['sep'], $r);
			array_splice($a, $opt['max_words']);
			$r = join($opt['sep'], $a);
		}
		
		if($opt['tolower'])
			$r = strtolower ($r);
		
		return (!empty($r) ? $r : NULL);
	}
}

//test
//$str = ' <p>aaa</p>  Łą_\dki  9900 łan, ĄąĆć ĘęŁł[]ŃńÓ= óŻźŹź   żabą^p^p^pBĄk M<>M?"AAA  ala ma kota "';
//var_dump($str, StringConvert::createSlug($str, array('max_words'=>12, 'sep'=>'^p', 'tolower'=>TRUE)));
//
//$str = 'a b c&nbsp; &gt;  &77; <p> ala </p> ><m>';
//var_dump($str, StringConvert::createSlug($str, array('max_words'=>12, 'sep'=>'-', 'tolower'=>TRUE, 'remove_entities' =>FALSE, 'remove_html_tag' =>FALSE)));
//var_dump(StringConvert::createSlug($str, array('max_words'=>12, 'sep'=>'-', 'tolower'=>TRUE, 'remove_entities' =>FALSE)));
//var_dump(StringConvert::createSlug($str, array('max_words'=>12, 'sep'=>'-', 'tolower'=>TRUE, 'remove_html_tag' =>FALSE)));
//var_dump(StringConvert::createSlug($str, array('max_words'=>12, 'sep'=>'-', 'tolower'=>TRUE)));
//
//var_dump(test('Ącki-', 'Acki'));
//var_dump(test('-Ącki-', 'Acki'));
//var_dump(test('     -Ąą Żż Źź/\\\// Ćć Łł Óó Ńń Ęę-', 'Aa-Zz-Zz-Cc-Ll-Oo-Nn-Ee'));
//var_dump(test('1234Av 89c', '1234Av-89c'));
//
//
//
//function test($str, $correct) {
//	return ($correct === StringConvert::createSlug($str));
//}
