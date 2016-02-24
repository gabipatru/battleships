<?php

/*
 * Grid where all the action happens.
 */

class Grid extends AbstractBattleship {
	
	private $theGrid;
	
	function __construct() {
		$this->setWidth(GRID_WIDTH);
		$this->setHeight(GRID_HEIGHT);
		$this->setLetterOffset(ord("A"));
		$this->setShotsFired(0);
		
		Battleships::log('Initializing ' . $this->getWidth() . 'x' . $this->getHeight() . ' grid');
		
		// grid chars, in case we want to change them during runtime
		$this->setCharDefault(GRID_DEFAULT_VALUE);
		$this->setCharHit(GRID_HIT);
		$this->setCharMiss(GRID_MISS);
		$this->setCharShip(GRID_SHIP);
		
		$this->theGrid = array();
		
		if ($this->sessionDataExists()) {
			return;
		}
		
		for ($i=0; $i<$this->getHeight(); $i++) {
			$this->theGrid[$i] = array();
			for ($j=0; $j<$this->getWidth(); $j++) {
				$this->updateGridPoint(array('x' => $j, 'y' => $i), $this->getCharDefault());
			}
		}
	}
	
	public function fetchTheGrid() {
		return $this->theGrid;
	}
	
	/*
	 * 2 necesary functions for transforming input to coordinates
	 */
	public function letterToCoordinate($letter) {
		return ord($letter) - $this->getLetterOffset();
	}
	
	public function numberToCoordinate($number) {
		return $number - 1;
	}
	
	/*
	 * For the web: save and load data from session
	 */
	public function save() {
		// check the enviroment
		$Config = Battleships::fetchTheConfig();
		if ($Config->getEnviroment() == 'cli') {
			Battleships::log('We do not have a session in cli enviroment');
			return false;
		}
		
		// save grid
		$_SESSION['battleships'] = $this->theGrid;
		$_SESSION['battleships_meta'] = array();
		
		// save ship data
		foreach (Battleships::getShips() as $shipName => $ship) {
			$ship->saveToSession($shipName);
		}
		
		// save shots fired
		$_SESSION['battleships_data']['shots_fired'] = $this->getShotsFired();
		return true;
	}
	
	public function load() {
		$Config = Battleships::fetchTheConfig();
		if ($Config->getEnviroment() == 'cli') {
			Battleships::log('We do not have a session in cli enviroment');
			return false;
		}
		
		if (isset($_SESSION['battleships']) && is_array($_SESSION['battleships'])) {
			// load grid
			$this->theGrid = $_SESSION['battleships'];
			
			// load ship data
			if (!isset($_SESSION['battleships_meta']) || !is_array($_SESSION['battleships_meta'])) {
				Battleships::log('Session problem! Ship data not found!');
				return false;
			}
			$Ships = Battleships::getShips();
			foreach ($_SESSION['battleships_meta'] as $shipName => $ship) {
				$Ships[$shipName]->setShipDirection($ship['direction']);
				$Ships[$shipName]->setShipHead($ship['head']);
				$Ships[$shipName]->setShipIsSunk($ship['ship_is_sunk']);
				
				$Ships[$shipName]->setHorizontalDirection(0);
				$Ships[$shipName]->setVerticalDirection(1);
			}
			
			// load shots fired
			if (isset($_SESSION['battleships_data']['shots_fired'])) {
				$this->setShotsFired($_SESSION['battleships_data']['shots_fired']);
			}
			else {
				$this->setShotsFired(0);
			}
		}
		return true;
	}
	
	public function reset() {
		$Config = Battleships::fetchTheConfig();
		if ($Config->getEnviroment() == 'cli') {
			Battleships::log('We do not have a session in cli enviroment');
			return false;
		}
		
		unset($_SESSION['battleships']);
		unset($_SESSION['battleships_meta']);
		unset($_SESSION['battleships_data']);
	}
	
	public function sessionDataExists() {
		$Config = Battleships::fetchTheConfig();
		if ($Config->getEnviroment() == 'cli') {
			Battleships::log('We do not have a session in cli enviroment');
			return false;
		}

		if (isset($_SESSION['battleships']) && is_array($_SESSION['battleships'])) {
			return true;
		}
		
		return false;
	}
	
	/*
	 * Returns the character that is set at some grid coordinates
	 */
	public function selectGridPoint($aPoint) {
		// check if the data is ok
		if (!is_array($aPoint) || !isset($aPoint['x']) || !isset($aPoint['y'])) {
			Battleships::log('Grid select received invalid values.');
			return;
		}
		
		// check if the coordinates are within the grid
		if ($aPoint['x'] < 0 || $aPoint['x'] >= $this->getWidth()) {
			Battleships::log('Grid select received invalid x coordinate');
			return;
		}
		if ($aPoint['y'] < 0 || $aPoint['y'] >= $this->getHeight()) {
			Battleships::log('Grid select received invalid y coorinate');
			return;
		}
		
		return $this->theGrid[$aPoint['y']][$aPoint['x']];
	}
	
	/*
	 * Changes the character at a given location on the grid
	 */
	public function updateGridPoint($aPoint, $value) {
		// check if the data is ok
		if (!is_array($aPoint) || !isset($aPoint['x']) || !isset($aPoint['y'])) {
			Battleships::log('Grid update received invalid values.');
			return;
		}
		
		// check if the new grid value is an accepted character
		if ($this->getCharDefault() != $value 
				&& $this->getCharHit() != $value
				&& $this->getCharMiss() != $value
				&& $this->getCharShip() != $value) {
			Battleships::log('Invalid character supplied to grid update.');
			return;
		}
		
		// check if the coordinates are within the grid
		if ($aPoint['x'] < 0 || $aPoint['x'] >= $this->getWidth()) {
			Battleships::log('Grid update received invalid x coordinate');
			return;
		}
		if ($aPoint['y'] < 0 || $aPoint['y'] >= $this->getHeight()) {
			Battleships::log('Grid update received invalid y coorinate');
			return;
		}
		
		$this->theGrid[$aPoint['y']][$aPoint['x']] = $value;
	}
	
	/*
	 * Function handles fire control.
	 * What happens when a coordinate is fired upon
	 */
	public function fireControl($aPoint) {

		// get the value of the coordinate
		$pointValue = $this->selectGridPoint($aPoint);
		if (!$pointValue) {
			Battleships::log('You fired at an invalid location! ('.$aPoint['x'].','.$aPoint['y'].')');
			return;
		}
		
		$this->setShotsFired($this->getShotsFired() + 1);
		
		if ($pointValue == $this->getCharShip()) {
			$this->updateGridPoint($aPoint, $this->getCharHit());
			Battleships::log('('.$aPoint['x'].','.$aPoint['y'].') HIT !!');
			return true;
		}
		else {
			$this->updateGridPoint($aPoint, $this->getCharMiss());
			Battleships::log('('.$aPoint['x'].','.$aPoint['y'].') MISS !');
			return false;
		}
	}
}