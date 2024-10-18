<?php
ob_start();
// if (session_status() == PHP_SESSION_NONE || !isset($_SESSION)) {
//     session_start();
// }

error_reporting(0);
error_reporting(E_ALL);

date_default_timezone_set('UTC');
ini_set("gd.jpeg_ignore_warning", 1);
define('ThrottleExceededErrorCode', '3001');
define('sleepSec', 30);

require_once(__DIR__ . "/../config/MANAGE_CONFIG.php");
define("EXTEND_BUCKET", (IS_DEVELOPMENT) ? '0000_local/' : '');
require_once(__DIR__ . "/_idiorm.php");

ORM::configure(array(
    'connection_string' => 'mysql:host=' . db_host . ';dbname=' . db_name . ';charset=utf8;',
    'username' => db_user,
    'password' => db_pass
));

ORM::configure('logging', true);
ORM::configure('caching', false);
ORM::configure('return_result_sets', true); // returns result sets

// Default: false,  Set to true when releasing website changes so no one uses it.
define("WEBSITE_UNDER_MAINTENANCE", false);
define("PHPFASTCACHE_EXPIRE_SEC", 30 * 24 * 60 * 60); // 30 days

header('Access-Control-Allow-Origin: *');
