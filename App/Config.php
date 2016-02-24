<?php

/*
 * This class holds several configuration values.
 * The values are not in the config file because they can be changed during runtime
 * Helps initiaize input and output
 */

class Config extends AbstractBattleship {
	
	function __construct() {
		$this->setEnviroment(php_sapi_name());
	}
	
	public function initOutput() {

		if ($this->getEnviroment() == 'apache2handler') {
			$Output = new OutputHtml();
			return $Output;
		}
		
		if ($this->getEnviroment() == 'cli') {
			$Output = new OutputConsole();
			return $Output;
		}
		
		return false;
	}
	
	public function initInput() {

		if ($this->getEnviroment() == 'apache2handler') {
			$Input = new InputHtml();
			return $Input;
		}

		if ($this->getEnviroment() == 'cli') {
			$Input = new InputConsole();
			return $Input;
		}
		
		return false;
	}
	
	/*
	 * For web: start the session only once
	 */
	public function sessionStart() {
		if ($this->getEnviroment() == 'cli') {
			return;
		}
		if (session_status() == PHP_SESSION_NONE) {
			ob_start();
			session_start();
		}
	}
}