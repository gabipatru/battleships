<?php
// display the top scores (hall of heroes)

if (Battleships::allShipsAreSunk()) {
	$topScores = Battleships::fetchHallOfHeroes()->fetchTopScores();
	$topHead   = Battleships::fetchHallOfHeroes()->getHeads();
	
	foreach ($topHead as $head) {
		echo "    " . $head . '\t\t';
	}
	echo "\n";
	
	foreach ($topScores as $playerData) {
		echo "   ".$playerData[0] . "\t\t" . $playerData[1] . "\t\t" . $playerData[2] . "\t\t" . $playerData[3] . "\n";
	}
}