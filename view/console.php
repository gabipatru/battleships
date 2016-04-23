<?php
$T = Battleships::fetchTheConfig()->getTranslations();
$spaceBetweenGrids = '      ';

echo "\n\n";

// display letters at the top of the grid
echo "    ";
foreach ($theGrid[1] as $key => $i) {
	echo chr($letterOffset + $key) . ' ';
}

// for second grid
echo $spaceBetweenGrids;
echo "    ";
foreach ($theGrid[1] as $key => $i) {
	echo chr($letterOffset + $key) . ' ';
}
echo "\n";

// display the rest
foreach ($theGrid as $key => $row) {
	// first grid
	$number = $key + 1;
	echo ($number >= 10 ? " " : "  ") . $number;
	foreach ($row as $key => $item) {
		echo " " . $this->displayGridChar($item, self::CHART_HITS);
	}
	
	// second grid
	echo $spaceBetweenGrids . " ";
	echo ($number >= 10 ? " " : "  ") . $number;
	foreach ($row as $key => $item) {
		echo " " . $this->displayGridChar($item, self::CHART_MISS);
	}
	echo "\n";
}

echo "\n\n";

// cheating display
require('console-cheating.php');

foreach (Battleships::getShips() as $shipName => $ship) {
	echo $T->__($shipName).": ". ($ship->getShipIsSunk() ? $T->__('sunk') : $T->__('floating')) . "\n";
}

if (Battleships::fetchTheConfig()->getPlayerName()) {
	echo $T->___('Hello, %s, welcome to Battleships', Battleships::fetchTheConfig()->getPlayerName());
}

if (Battleships::fetchTheGrid()->getLastShot() !== -1) {
	echo "\n" . $T->__('Last Shot'). " : ".(Battleships::fetchTheGrid()->getLastShot() ? $T->__('hit').'!' : $T->__('miss'));
}
echo "\n" . $T->__('Shots fired') . ": ".Battleships::fetchTheGrid()->getShotsFired();
echo "\n" . $T->__('Shots hit') . " : ".Battleships::fetchTheGrid()->getShotsHit();
echo "\n" . $T->__('Shots missed') .": ".Battleships::fetchTheGrid()->getShotsMissed();

echo "\n\n";

if (!Battleships::allShipsAreSunk()) {
	if (!Battleships::fetchTheConfig()->getPlayerName()) {
		echo $T->__('Enter your name') . ' ';
	}
	else {
		echo $T->__('Enter coordinates (row, col), e.g. A5') . ' ';
	}
}
else {
	echo "\n\n" . $T->__('THE END'). " !!! \n\n";
}

require('console-hall-of-heroes.php');