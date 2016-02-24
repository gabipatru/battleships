<?php

class OutputHtml extends Output {
	
	function __construct() {
		$this->setOutputTemplate('html.php');
		Battleships::log('Initialized web output');
	}
}