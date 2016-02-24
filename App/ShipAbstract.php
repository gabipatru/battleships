<?php

/*
 * All ship types are derived from this.
 * This class handles ship deployment.
 */

class ShipAbstract extends AbstractBattleship {
	
	/*
	 * This function handles ship deployment of all types.
	 */
	public function deploy($Grid) {
		// sanity checks
		if (!is_object($Grid)) {
			return false;
		}
		if ($Grid->sessionDataExists()) {
			return false;
		}
		
		$shipIsDeployed = false;
		$nrTries = 0;
		
		$this->setShipIsSunk(true);
		
		$this->setHorizontalDirection(0);
		$this->setVerticalDirection(1);
		
		$aPoint = array();

		while ($shipIsDeployed === false && $nrTries <= MAX_TRIES) {
			// get random grid values
			$aPoint['x'] = rand(0, $Grid->getWidth() - 1);
			$aPoint['y'] = rand(0, $Grid->getHeight() - 1);

			// get ship direction, horizontal or vertical
			$shipDirection = rand(0, 1);

			// check if we can place the ship at those coordinates
			if ($shipDirection === 0 && $aPoint['x'] + $this->getShipSize() >= $Grid->getWidth()) {
				// we cannot place the ship outside of the grid, it goes beyond max width
				$nrTries++;
				continue;
			}
			if ($shipDirection === 1 && $aPoint['y'] + $this->getShipSize() >= $Grid->getHeight()) {
				// we cannot place the ship outside of the grid, it goes beyond max height
				$nrTries++;
				continue;
			}
						
			// we can place the ship at the selected coordinates
			$shipIsDeployed = $this->deployShip($Grid, $aPoint, $shipDirection);
			if ($shipIsDeployed) {
				$this->setShipIsSunk(false);
			}
		}
		
		// check if we reached limit of tries
		if ($nrTries > MAX_TRIES) {
			Battleships::log('Ship '.__CLASS__ . ' reached maximum number of deploy attempts and will not be deployed');
			return false;
		}
		
		return true;
	}
	
	/*
	 * This function effectively deploys the ship on the grid.
	 * Checks if the ship overlaps other ships
	 */
	private function deployShip($Grid, $aPoint, $shipDirection) {
		// update ship properties;
		$this->setShipDirection($shipDirection);
		$this->setShipHead($aPoint);
		
		$dx = ($shipDirection == $this->getHorizontalDirection() ? 1 : 0);
		$dy = ($shipDirection == $this->getVerticalDirection() ? 1 : 0);
		
		// check if we can place the ship at the given location
		$savePoint = $aPoint;
		for($i=0; $i<$this->getShipSize(); $i++) {
			$gridValue = $Grid->selectGridPoint($aPoint);
			if ($gridValue == $Grid->getCharShip()) {
				return false;
			}
			$aPoint['x'] += $dx;
			$aPoint['y'] += $dy;
		}
		
		// update grid
		$aPoint = $savePoint;
		for($i=0; $i<$this->getShipSize(); $i++) {
			$Grid->updateGridPoint($aPoint, $Grid->getCharShip());
			$aPoint['x'] += $dx;
			$aPoint['y'] += $dy;
		}
		
		return true;
	}
	
	/*
	 * Damage control for individual ship.
	 * Mark the ship when it is sunk
	 */
	public function damageControl($Grid) {
		if ($this->getShipIsSunk()) {
			return;
		}
		$shipDirection = 	$this->getShipDirection();
		$aPoint = 			$this->getShipHead();
		
		$dx = ($shipDirection == $this->getHorizontalDirection() ? 1 : 0);
		$dy = ($shipDirection == $this->getVerticalDirection() ? 1 : 0);
				
		// if we find a single point where the ship isn't hit, everything is ok
		for($i=0; $i<$this->getShipSize(); $i++) {
			$value = $Grid->selectGridPoint($aPoint);
			if ($value == $Grid->getCharShip()) {
				return;
			}
			$aPoint['x'] += $dx;
			$aPoint['y'] += $dy;
		}
		
		$this->setShipIsSunk(true);
	}
	
	/*
	 * Save ship to session and load ship from session
	 */
	public function saveToSession($shipName) {
		if (!$shipName) {
			Battleships::log('No ship name specified when saving to session');
			return false;
		}
		
		$_SESSION['battleships_meta'][$shipName] = array();
		$_SESSION['battleships_meta'][$shipName]['direction'] = $this->getShipDirection();
		$_SESSION['battleships_meta'][$shipName]['head'] = $this->getShipHead();
		$_SESSION['battleships_meta'][$shipName]['ship_is_sunk'] = $this->getShipIsSunk();
		
		return true;
	}
	
	public function loadFromSession($shipName) {
		if (!$shipName) {
			Battleships::log('No ship name specified when loading from session');
			return false;
		}
		
		$this->setShipDirection($this->getDirection());
		$this->setShipHead($this->getHead());
		$this->setShipIsSunk($this->getShipIsSunk());
	}
}