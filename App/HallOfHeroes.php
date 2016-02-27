<?php

/*
 * This class implements top scores in a file
 */
class HallOfHeroes extends AbstractBattleship {
	private static $topScores;
	
	function __construct() {
		$this->setFileName(SAVEFILE_NAME);
		$this->setHeads(array('Name', 'Time', 'Hits', 'Misses'));
	}
	
	/*
	 * Load data from file, save data to file
	 */
	public function loadData() {
		if (!file_exists(DATA_DIR . '/' . $this->getFileName())) {
			return false;
		}
		
		$fis = fopen(DATA_DIR . '/' . $this->getFileName(), "r");
		if (!$fis) {
			Battleships::log('Could not open csv file!');
		}
		
		// prepare data for sorting
		$names = array();
		$scores = array();
		$hits = array();
		$misses = array();
		while ($csvLine = fgetcsv($fis)) {
			if (is_array($csvLine)) {
				$names[] = $csvLine[0];
				$scores[] = $csvLine[1];
				$hits[] = $csvLine[2];
				$misses[] = $csvLine[3];
			}
		}
		
		array_multisort($scores, SORT_ASC, $names, SORT_ASC, $hits, SORT_ASC, $misses, SORT_ASC);
		
		// prepare static data
		foreach ($names as $key => $value) {
			self::$topScores[] = array($names[$key], $scores[$key], $hits[$key], $misses[$key]);
		}
		
		Battleships::log('Loaded top scores from file '.$this->getFileName());
	}
	
	public function saveData($data) {
		if (!$data || !is_array($data) || empty($data[0]) || empty($data[1])) {
			return false;
		}
		
		if (!file_exists(DATA_DIR . '/' . $this->getFileName())) {
			return false;
		}
		
		$fis = fopen(DATA_DIR . '/' . $this->getFileName(), "a");
		if (!$fis) {
			Battleships::log('Could not open csv file!');
		}
		
		$res = fputcsv($fis, $data);
		if ($res === false) {
			Battleships::log('ERROR! Could not save data to file '.$this->getFileName());
		}
		else {
			Battleships::log('Saved data to file '.$this->getFileName());
		}
	}
	
	public function fetchTopScores() {
		return self::$topScores;
	}
}