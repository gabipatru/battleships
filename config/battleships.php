<?php

/*
 * This is the main config file
 */

// physical folders
define('BASE_DIR', '/var/www/battleships');
define('APP_DIR', BASE_DIR . '/App');
define('CONFIG_DIR', BASE_DIR . '/config');
define('LOG_DIR', BASE_DIR . '/logs');
define('VIEW_DIR', BASE_DIR . '/view');

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