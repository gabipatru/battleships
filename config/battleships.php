<?php

/*
 * This is the main config file
 */

// physical folders
define('BASE_DIR', '/var/www/battleships');
define('APP_DIR', BASE_DIR . '/App');
define('TRAITS_DIR', APP_DIR . '/traits');
define('CONFIG_DIR', BASE_DIR . '/config');
define('LOG_DIR', BASE_DIR . '/logs');
define('VIEW_DIR', BASE_DIR . '/view');
define('DATA_DIR', BASE_DIR . '/data');
define('TRANSLATIONS_DIR', BASE_DIR . '/translations');

// http
define('HTTP_MAIN', 'http://www.battleships.ro');
define('HtTP_CSS', HTTP_MAIN . '/static/css');

// grid
define('GRID_WIDTH', 10);
define('GRID_HEIGHT', 10);
define('GRID_DEFAULT_VALUE', '.');
define('GRID_HIT', 'x');
define('GRID_MISS', '-');
define('GRID_SHIP', 'o');

// ships
define('PATROL_BOAT_SIZE', 2);
define('BATTLESHIP_SIZE', 4);
define('CARRIER_SIZE', 5);

// ship deployment
define('MAX_TRIES', 100);								// to avoid potential infinite loops

// ship names
define("NAME_PATROLBOAT1", "PatrolBoat1");
define("NAME_PATROLBOAT2", "PatrolBoat2");
define("NAME_BATTLESHIP", "Battleship");
define("NAME_CARRIER", "Carrier");

// savefile name
define("SAVEFILE_NAME", "heroes.csv");

define('LANGUAGE', 'ro');
require_once(CONFIG_DIR . '/translations.php');