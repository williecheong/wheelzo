<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
| Custom stuff
|--------------------------------------------------------------------------
*/

require(APPPATH.'/config/secrets.php');
define('CURRENT_VERSION',       '9.001');
define('WHEELZO_DESCRIPTION', 	"Better rideshare and carpooling for people around Kingston, Ottawa, Montreal, Kitchener, Waterloo, Ontario and the Greater Toronto Area");

define('WHEELZO_DELIMITER',     '{?}');
define('NOTIFY_ASSIGNED',       'A');
define('NOTIFY_COMMENT',        'C');
define('NOTIFY_REMOVED',        'R');
define('NOTIFY_DELETED',        'D');
define('NOTIFY_INVITED',        'I');
define('NOTIFY_VOUCHED',        'V');
define('NOTIFY_REVIEWED',       'W');
define('NOTIFY_IMPORT',         'P');

define('WHEELZO_RIDE_PRICE_LOWER', 1);
define('WHEELZO_RIDE_PRICE_UPPER', 60);
define('WHEELZO_RIDE_CAPACITY_LOWER', 1);
define('WHEELZO_RIDE_CAPACITY_UPPER', 7);
define('WHEELZO_USER_FBGROUPS_LIMIT', 3);
define('WHEELZO_PAYMENT_COMMISSION', 0.2);

define('WHEELZO_FACEBOOK_ACCESS_TOKEN_EXPIRY', '1442551230');
define('WHEELZO_FACEBOOK_ACCESS_TOKEN', 'CAAEApvyP7WsBAINJThBeKuzWG3ElfFiZAxLfOjJaZBXOWIhXYzu9cnDLJpOJGBK21ondAFENx7fZCXEH42IWp0ZCMQ32Iau1I6hq0u5oujSgm7aGbAsZAeAgyFZAds3P2RF9eesktMQufrGAK3eSsnZCQdFlUgbsHqPue4ZBGrilZBUErJ2WNHAlfZCNrTxJaAwUS1vgvjZA64Eo1nREnzZC0AHf');

// ROOT: Willie, Max, Dennis
$GLOBALS['WHEELZO_ROOT'] = array('616512487', '100001375166320', '592441839');

// TECH: ROOT, Terry, Jathusan, Ryan
$GLOBALS['WHEELZO_TECH'] = array_merge( 
                                $GLOBALS['WHEELZO_ROOT'],  
                                array('1612834909', '1288730489', '10153365790115325')
                            );

// BDEV: ROOT, TECH, Muz
$GLOBALS['WHEELZO_BDEV'] = array_merge(
                                $GLOBALS['WHEELZO_TECH'],
                                array('100000604400266')
                            );

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */