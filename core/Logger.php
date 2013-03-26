<?php namespace core;

class Logger {
	
	private $err;
	
	public function setError(\Exception $err) {
		$this->err = $err;
	}

	public function printError() {
		echo $this->formatErrorMsg();
	}
	
	public function saveError() {
		$logFilePath = \core\Config::get('mainPath').'app/log/log.txt';
		if(!is_dir(dirname($logFilePath)))
			mkdir(dirname($logFilePath));
		$logFileHandler = fopen($logFilePath, 'a+');
		fwrite($logFileHandler, $this->formatErrorMsg().PHP_EOL);
		fclose($logFileHandler);
	}
	
	private function formatErrorMsg() {
		return sprintf('[ERROR][%s] file:%s, line:%s, code:%s, msg:%s', 
			date(DATE_ATOM),
			$this->err->getFile(),
			$this->err->getLine(),
			$this->err->getCode(),
			$this->err->getMessage()
		);
	}
	
}
