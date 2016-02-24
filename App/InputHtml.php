<?php

/*
 * This class handles input from web (POST)
 */

class InputHtml extends Input {
	
	public function readInput() {
		$line = null;
		if (isset($_POST['fire'])) {
			$line = trim($_POST['fire']);
		}
		
		return $line;
	}
	
	public function requestNewGame() {
		if (isset($_POST['new_game']) && $_POST['new_game'] == 'New Game') {
			return true;
		}
		else {
			return false;
		}
	}
}