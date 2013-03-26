<?php namespace core\router;

interface Route {
	public function match($extUrl);
	public function getInternalUrl($extUrl);	
	public function getExternalUrl($urlOptions);
	public function getGender();
}
