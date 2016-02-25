<?php
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

foreach (Battleships::getShips() as $shipName => $ship) {
	echo $shipName.": ". ($ship->getShipIsSunk() ? 'sunk' : 'floating') . "\n";
}

if (Battleships::fetchTheGrid()->getLastShot() !== -1) {
	echo "\nLast shot : ".(Battleships::fetchTheGrid()->getLastShot() ? 'hit!' : 'miss');
}
echo "\nShots fired: ".Battleships::fetchTheGrid()->getShotsFired();
echo "\nShots hit  : ".Battleships::fetchTheGrid()->getShotsHit();
echo "\nShots miss : ".Battleships::fetchTheGrid()->getShotsMissed();

echo "\n\n";

if (!Battleships::allShipsAreSunk()) {
	echo "Enter coordinates (row, col), e.g. A5: ";
}
else {
	echo "\n\nTHE END !!! \n\n";
}