<?php

/*
 * This class creates basic setters and getters.
 * All classes except main application class are extended from this
 */

abstract class AbstractBattleship {
	
	public function __call($function, $args) {
		$prefix = substr($function, 0, 3);
		$property = strtolower(substr($function, 3));

		// for setter
		if ($prefix == 'set' && count($args) == 1) {
			$this->$property = $args[0];
			return;
		}
		
		// for getter
		if ($prefix == 'get' && count($args) == 0) {
			if (isset($this->$property)) {
				return $this->$property;
			}
			else {
				return null;
			}
		}
		
		throw new Exception('Function not defined: '.$function);
	}
}