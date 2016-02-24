<?php

class OutputConsole extends Output {
	
	function __construct() {
		$this->setOutputTemplate('console.php');
		Battleships::log('Initialized console output');
	}
}