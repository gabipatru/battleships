<?php

/*
 * The patrol boat
 */

 class ShipPatrolBoat extends ShipAbstract {
 	
 	function __construct() {
 		$this->setShipSize(PATROL_BOAT_SIZE);
 	}
 }