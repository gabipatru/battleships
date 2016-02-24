<?php

/*
 * The mighty battleship
 */

class ShipBattleship extends ShipAbstract {
	 
	function __construct() {
		$this->setShipSize(BATTLESHIP_SIZE);
	}
}