<?php

/*
 * The carrier
 */

class ShipCarrier extends ShipAbstract {
	
	function __construct() {
		$this->setShipSize(CARRIER_SIZE);
	}
}