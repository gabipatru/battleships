<?php

/*
 * This class supports multiple output types.
 * It is used for displaying the grid
 */
abstract class Output extends AbstractBattleship {
	
	const CHART_HITS = 1;
	const CHART_MISS = 2;
	
	public function display($Grid) {
		$theGrid = $Grid->fetchTheGrid();
		$letterOffset = $Grid->getLetterOffset();
		
		if (!file_exists(VIEW_DIR . '/' . $this->getOutputTemplate())) {
			Battleships::log('View file ' . $this->getOutputTemplate() . ' not found! Cannot display anything');
		}
		
		require(VIEW_DIR . '/' . $this->getOutputTemplate());
	}
	
	/*
	 * This function handles the display of characters on the grid
	 * It hides ships and other things when needed
	 */
	protected function displayGridChar($char, $chartType) {
		// sanity checks
		if (!$char) {
			Battleships::log('No char provided');
			return;
		}
		if ($chartType != self::CHART_HITS && $chartType != self::CHART_MISS) {
			Battleships::log('Invalid chart type');
			return;
		}
		
		$Config = Battleships::fetchTheConfig();
		$Grid = Battleships::fetchTheGrid();
		
		// the default character is always displayed as such on any grid
		if ($char == $Grid->getCharDefault() || $Config->getEnableDebug()) {
			return $char;
		}
		
		// hide characters on hit chart
		if ($chartType == self::CHART_HITS && !$Config->getEnableDebug() && $char != $Grid->getCharHit()) {
			return $Grid->getCharDefault();
		}
		
		// hide characters on miss chart
		if ($chartType == self::CHART_MISS && !$Config->getEnableDebug() && $char != $Grid->getCharMiss()) {
			return $Grid->getCharDefault();
		}
		
		return $char;
	}
}