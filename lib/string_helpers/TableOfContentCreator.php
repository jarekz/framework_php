<?php namespace lib\string_helpers;

//TODO
/*
 * istnieje pewna niescislosc iterpretacji zagniezdzonych znacznikow tego samego typu
 * 
 * dla tresci oryginalnej: <p class="css">a<p>bold</p></p>
 * wywolania: TableOfContentCreator::create(array('P'), $str, 'id');
 * klasa zwraca ciag: <P class="css" id='toc-0'>a<p>bold</P></p>
 * 
 * widac uzyte w klasie wyraznie regularne wyszukuje ciag do miejsca wystapienia pierwszego znacznika zamykajacego
 * (pierwsze duze /P, bo:
 * 1. regex jest nieczule na wielkosc znakow, 
 * 2. znaczniki sa podmieniane)
 * jesli po pierwszym znaczniku zamykajacym bedzie jakas tresc, w spisie tresci (ta tresc) zostanie obcieta
 * 
 */

//$str = '<p class="css">a<p>bold</p></p>';
//TableOfContentCreator::create(array('P'), $str, 'id');
//var_dump(TableOfContentCreator::getContent());

class TableOfContentCreator {

	private static $_content;
	private static $_sourceTableOfContent;
	private static $_htmlTableOfContent;

	/**
	 * przeszukuje treść i tworzy spis treści na podstawie listy tagów,
	 * 
	 * wyszukiwanie polega na odnajdywaniu: pierwszy tag z lsty -> rozdzial, drugi tag z listy -> podrodzial, trzeci tag...   
	 * 
	 * wyniki dzialania tej metody mozna pobrac korzystajac z metod:
	 * 
	 * TableOfContentCreator::getContent() - zwraca oryginalna tresc z kotwicami
	 * 
	 * TableOfContentCreator::getSourceTableOfContent() - zwraca surowy, wygenerowany spis tresci (zagniezdzone obiekty stdClass)
	 * 
	 * TableOfContentCreator::getHtmlTableOfContent() - zwraca wygenerowany spis tresci opakowany w znaczniki html (string)
	 * 
	 * @param   array $searchTags tablica tagów
	 * @param   string $content treść
	 * @param   string $anchorAttr = 'id' (Optional) atrubyt, który zostanie wykorzystany do utowrzenia kotwicy
	 * @param   string $anchorPref = 'toc' (Optional) prefix wartości wstawianej do kotwicy (toc-nr_roz-nr_podroz np toc-0-0)
	 * @return  NULL
    */
	public static function create(array $searchTags, $content, $anchorAttr='id', $anchorPref='toc') {
		self::$_content = NULL;
		self::$_sourceTableOfContent = NULL;
		self::$_htmlTableOfContent = NULL;
		
		if(!empty($searchTags)) {
			$tableOfContentsSource = new stdClass();
			$content = self::createTableAndContent($searchTags, $content, $tableOfContentsSource, $anchorAttr, $anchorPref);

			self::$_sourceTableOfContent = $tableOfContentsSource;
			self::$_content = $content;
			self::$_htmlTableOfContent = self::formatSourceTableOfContent($tableOfContentsSource, array('ul'));
		}
	}
	
	/**
	 * zwraca oryginalna tresc z kotwicami
	 * 
	 * @return  string
    */
	public static function getContent() {
		return self::$_content;
	}
	
	/**
	 * zwraca wygenerowany spis tresci
	 * 
	 * @return  stdClass
    */
	public static function getSourceTableOfContent() {
		return self::$_sourceTableOfContent;
	}
	
	/**
	 * zwraca wygenerowany spis tresci opakowany w znaczniki html 
	 * 
	 * @return  string
    */
	public static function getHtmlTableOfContent() {
		return self::$_htmlTableOfContent;
	}

	/**
	 * zwraca spis tresci opakowany w znaczniki html
	 * 
	 * @param   stdClass $source surowy spis tresci zapisany jako zagniezdzone obiekty stdClass
	 * @param   array $wrapTags tablica opakowujacych znacznikow html, pierwszy tag z lsty -> rozdzial, drugi tag -> podrodzial..., minimalnie wystarczy podac jeden tag
	 * @return  string spis tresci
    */
	public static function formatSourceTableOfContent(stdClass $source, array $wrapTags) {
		if(empty($wrapTags)) throw new InvalidArgumentException('Not found wrapper tags.'); 
		return self::formatSourceTableOfContentHelper($source, $wrapTags);
	}

	private static function formatSourceTableOfContentHelper(stdClass $source, array $wrapTags) {
		$wrapTag = array_shift($wrapTags);
		if(empty($wrapTags)) $wrapTags[] = $wrapTag;
		
		$wrapTag = self::wrapTagsFactor($wrapTag);
		
		$html = $wrapTag['openOuter'];
		$aTag = "<a href='#%s'>%s</a>";
		
		foreach($source as $s) {
			if(!isset($s->title, $s->anchorVal))
				throw new InvalidArgumentException('Not found title or anchorVal properties in source.'); 

			$html .= $wrapTag['openInner'].sprintf($aTag, $s->anchorVal, $s->title);
			
			if(isset($s->subchapters) && count(get_object_vars($s->subchapters)) > 0) {
				$html .= self::formatSourceTableOfContentHelper($s->subchapters, $wrapTags);
			}
			
			$html .= $wrapTag['closeInner'].$wrapTag['closeOuterFirstPlace'];
		}
		
		return $html.$wrapTag['closeOuterSecondPlace'];
	}
	
	private static function wrapTagsFactor($wrapTag) {
		$r = array(
			'openOuter'				=> "<$wrapTag>",
			'closeOuterFirstPlace'	=> "",
			'closeOuterSecondPlace'	=> "</$wrapTag>",
			'openInner'				=> "",
			'closeInner'	=> "",
		);
		
		switch ($wrapTag) {
			case 'ul':
			case 'ol':
				$r['openInner'] = '<li>';
				$r['closeInner'] = '</li>';
				break;
			
			case 'dl':
				$r['openInner'] = '<dt>';
				$r['closeInner'] = '</dt>';
				break;

			default:
				$r['openOuter'] = '';
				$r['closeOuterFirstPlace'] = "</$wrapTag>";
				$r['closeOuterSecondPlace'] = "";
				$r['openInner'] = "<$wrapTag>";
				$r['closeInner'] = '';
				break;
		}
		
		return $r;
	}

	private static function createTableAndContent($searchTags, $content, $table, $anchorAttr, $anchorVal) {
		$tag = preg_quote(array_shift($searchTags));
		$regex = "/<{$tag}( [^>]*)?>(.*?)<\/{$tag}>/si";
		
		
		$regex = "/<{$tag}( [^>]*)?>(.*?)<\/{$tag}>/si";
		
		
		
		preg_match_all($regex, $content, $titles, PREG_SET_ORDER);
		$substrings = preg_split($regex, $content);
		$content = array_shift($substrings);
		
		foreach($substrings as $k => $substring) {
			$attrs = $titles[$k][1];
			$txt = $titles[$k][2];
			
			//table of content
			$table->{$k} = new stdClass();
			$table->{$k}->title = $txt;
			$table->{$k}->subchapters = new stdClass();
			
			//content
			$_anchorVal = "$anchorVal-$k";
			$table->{$k}->anchorVal = self::addAnchor($attrs, $anchorAttr, $_anchorVal);
			$content .= "<$tag$attrs>$txt</$tag>";
			if(!empty($substring)) $content .= self::createTableAndContent($searchTags, $substring, $table->{$k}->subchapters, $anchorAttr, $_anchorVal);
		}
		
		return $content;
	}
	
	private static function addAnchor(&$attrs, $anchorAttr, $anchorVal) {
		$anchorAttr = preg_quote($anchorAttr);
		$regex = "/$anchorAttr=['\"](.*?)['\"]/";
		if(preg_match($regex, $attrs, $m)) {
			return $m[1];
		} else {
			$attrs = rtrim($attrs) . " $anchorAttr='$anchorVal'";
			return $anchorVal;
		}
	}
}