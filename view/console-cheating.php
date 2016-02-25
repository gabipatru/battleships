<?php if (Battleships::fetchTheConfig()->getCheating() === true) {
	// display letters at the top of the grid
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
			echo " " . $item;
		}
		echo "\n";
	}
	
	echo "\n\n";
}
