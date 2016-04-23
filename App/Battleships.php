<?php

/*
 * This is the main application class
 */

class Battleships {
	
	private static $Config;
	private static $Grid;
	private static $Input;
	private static $Output;
	private static $PatrolBoat1;
	private static $PatrolBoat2;
	private static $Battleship;
	private static $Carrier;
	
	private static $HallOfHeroes;
	
	/*
	 * Autoloading
	 */
	private static function registerAutoload() {
		return spl_autoload_register(array(__CLASS__, 'autoLoadClass'));
	}
	
	private static function autoLoadClass($className) {
		require(APP_DIR . '/' . $className . '.php');
	}
	
	
	/*
	 * Main functions
	 */
	private static function init() {
		self::registerAutoload();
		
		// some basic configs
		self::$Config = new Config();
		self::$Config->setLogFile('main_log.log');
		self::$Config->setEnableLog(true);
		self::$Config->setEnableDebug(false);
		self::$Config->setCheating(false);

		// translations
		$T = new Translations(LANGUAGE);
		self::$Config->setTranslations($T);
		
		self::log('Start application init');
		
		self::$HallOfHeroes = new HallOfHeroes();
		
		self::$Config->sessionStart();
		
		// grid initialization
		self::$Grid = new Grid();
		if (!self::$Grid) {
			self::log('Failed to initialize GRID! Exiting.');
			exit;
		}
		
		// input initialization
		self::$Input = self::$Config->initInput();
		if (!self::$Input) {
			self::log('Unknown Input Enviroment! Only cli and apache2 supprted! Exiting.');
			exit;;
		}
		
		// output initialization 
		self::$Output = self::$Config->initOutput();
		if (!self::$Output) {
			self::log('Unknown Output Enviroment! Only cli and apache2 supprted! Exiting.');
			exit;;
		}
		 
		// ship initialization and deployment
		self::$PatrolBoat1 = new ShipPatrolBoat();
		self::$PatrolBoat1->deploy(self::$Grid);
		
		self::$PatrolBoat2 = new ShipPatrolBoat();
		self::$PatrolBoat2->deploy(self::$Grid);
		
		self::$Battleship = new ShipBattleship();
		self::$Battleship->deploy(self::$Grid);
		
		self::$Carrier = new ShipCarrier();
		self::$Carrier->deploy(self::$Grid);
	}
	
	public static function run() {
		self::init();

		if (self::$Config->getEnviroment() == 'cli') {
			self::runConsole();
		}
		else {
			self::runHtml();
		}
		
		self::log('Application ran without errors.');
	}
	
	private static function runConsole() {
		// translations
		self::$Config->getTranslations()->setModule('console');
		self::$Config->getTranslations()->loadTranslations();
		
		while (!self::allShipsAreSunk()) {
			system('clear');
			
			self::$Output->display(self::$Grid);
			self::process();
		}
		
		system('clear');
		self::$Output->display(self::$Grid);
	}
	
	private static function runHtml() {
		// translations
		self::$Config->getTranslations()->setModule('html');
		self::$Config->getTranslations()->loadTranslations();

		// check if we must start a new game
		if (self::$Input->requestNewGame()) {
			self::$Grid->reset();
			header("Location:" . HTTP_MAIN);
			exit;
		}
		
		self::$Grid->load();

		// process data
		self::process();
		
		self::$Output->display(self::$Grid);
		ob_flush();
		self::$Grid->save();

	}
	
	/*
	 * Function processes input data, updates grid data and ships data
	 */
	private static function process() {
		$mixedPoint = self::$Input->readInput();
		
		// process input if any
		if ($mixedPoint && !self::$Config->getPlayerName()) {
			self::$Config->setPlayerName($mixedPoint);
			
			// game timer starts after player enters his name
			if (!self::$Config->getPlayerStart()) {
				self::$Config->setPlayerStart(time());
			}
		}
		elseif ($mixedPoint == 'show') {
			self::$Config->setCheating(true);
		}
		elseif ($mixedPoint) {
			$aPoint = self::processInput($mixedPoint);
				
			// compute results
			$result = self::$Grid->fireControl($aPoint);
			self::shipsDamageControl();
		}
	}
	
	/*
	 * Process the input and return an array of coordinates
	 */
	private static function processInput($mixedPoint) {
		$aPoint = array();
		preg_match('/[A-Z]/', $mixedPoint, $match);
		$aPoint['x'] = $match[0];
		$aPoint['x'] = self::$Grid->letterToCoordinate($aPoint['x']);
		preg_match('/[0-9]+/', $mixedPoint, $match);
		$aPoint['y'] = $match[0];
		$aPoint['y'] = self::$Grid->numberToCoordinate($aPoint['y']);
		
		return $aPoint;
	}
	
	/*
	 * Damage control: when a ship is hit, we compute the result
	 */
	private static function shipsDamageControl() {
		foreach (self::getShips() as $ship) {
			$ship->damageControl(self::$Grid);
		}
		
		// we need to o some ops when the game ends like save score, load hall of heroes
		if (self::allShipsAreSunk()) {
			if (!self::$Config->getPlayerEnd()) {
				self::$Config->setPlayerEnd(time());
			}
			
			// do some checks before saving data to file
			if (self::$Config->getPlayerName() && self::$Grid->getShotsHit() && self::$Grid->getShotsMissed()) {
				self::$HallOfHeroes->saveData(array(
					self::$Config->getPlayerName(),
					self::$Config->getPlayerEnd() - self::$Config->getPlayerStart(),
					self::$Grid->getShotsHit(),
					self::$Grid->getShotsMissed()
				));
			}
			
			self::loadHallOfHeroes();
		}
	}
	
	
	/*
	 * Basic logging
	 */
	public static function log($value) {
		if (!self::$Config->getEnableLog() || !$value) {
			return;
		}
		
		$logFile = self::$Config->getLogFile();
		$value = date('[Y-m-d H:i:s]: ') . $value . "\n";
		
		$file = fopen(LOG_DIR . '/' . $logFile, "a");
		fputs($file, $value);
	}
	
	/*
	 * We need the static properties in some places
	 */
	public static function getShips() {
		return array(
			NAME_PATROLBOAT1 	=> self::$PatrolBoat1,
			NAME_PATROLBOAT2	=> self::$PatrolBoat2,
			NAME_BATTLESHIP 	=> self::$Battleship,
			NAME_CARRIER		=> self::$Carrier
		);
	}
	
	public static function allShipsAreSunk() {
		if (	self::$PatrolBoat1->getShipIsSunk()
				&&	self::$PatrolBoat2->getShipIsSunk()
				&&	self::$Battleship->getShipIsSunk()
				&&	self::$Carrier->getShipIsSunk()) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/*
	 * We sometimes need the grid and the config
	 */
	public static function fetchTheGrid() {
		return self::$Grid;
	}
	
	public static function fetchTheConfig() {
		return self::$Config;
	}
	
	public static function fetchHallOfHeroes() {
		return self::$HallOfHeroes;
	}
	
	/*
	 * Load endgame data
	 */
	public static function loadHallOfHeroes() {
		self::$HallOfHeroes->loadData();
	}
}