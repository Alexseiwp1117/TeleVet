<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',                                'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',                           'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',                                            'ab');
define('FOPEN_READ_WRITE_CREATE',                                       'a+b');
define('FOPEN_WRITE_CREATE_STRICT',                                     'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',                                'x+b');
define('PARSE_APP_KEY',							'f111I4p9dAKPF933lnY5KZnPShNJVE1q93M9k4Bq');
define('PARSE_REST_KEY',						'ar2eKsX5SgHD1jIC3qhgGAgvvj4iHDGAoEZ7RII0');
define('PARSE_MASTER_KEY',						'pN5kFt8SiXr0FvUciRjO5wOpAgfpH3e1yFs7chX8');
define('MST',								'America/Denver');
define('PST',								'America/Los_Angeles');
define('CST',								'America/Chicago');
define('EST',								'America/New_York');
define('BRAIN_PUB_KEY',							'bpz2t6q2f2dh64fs');
define('BRAIN_PRI_KEY',							'9acef50c8b76e0589dabd461051d4fb1');
define('BRAIN_MEC_ID',							'679k2vy8dzw9t8dd');

/* End of file constants.php */
/* Location: ./application/config/constants.php */