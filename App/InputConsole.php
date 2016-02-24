<?php

/*
 * This class handles input from the console.
 */

class InputConsole extends Input {
	
	public function readInput() {
		$handle = fopen ("php://stdin","r");
		$line = trim(fgets($handle));
		
		return $line;
	}
}