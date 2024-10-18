<?php

ini_set('max_execution_time', 300); //300 seconds = 5 minutes

error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('display_errors', 1);
ini_set('mysql.connect_timeout', 300);
ini_set('default_socket_timeout', 300);
ini_set("pcre.backtrack_limit", "5000000");

//error_reporting(E_ERROR | E_WARNING | E_PARSE);

date_default_timezone_set('UTC');

if (isset($_SERVER) && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	header('Access-Control-Allow-Origin: *');
	// header('Access-Control-Allow-Headers: X-Requested-With');
	header("HTTP/1.1 200 OK");

	// header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Content-Range, Content-Disposition");
	header('Access-Control-Allow-Methods: GET, HEAD, OPTIONS, POST, PUT');

	die();
}

$start_service = microtime(true);

header("Content-type: application/json; charset=utf-8");

//ob_start('fatal_error_handler');
include("_DEFINE.php");
include("_SUPPORT.php");
require_once('_DATABASE.php');
include("projectConst.php");
if (IS_PRODUCTION) {
	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
}

if (WEBSITE_UNDER_MAINTENANCE == true) {
	$outputjson['message'] = "The website is under maintenance. Please check back soon.";
	$outputjson['error'] = "The website is under maintenance. Please check back soon.";
	$outputjson['success'] = 0;

	$response_string = json_encode(($outputjson), JSON_PRETTY_PRINT);
	echo $response_string;

	return;
}
array_filter($_POST, 'trim_value');    // the data in $_POST is trimmed
global $outputjson, $gh, $db, $debug_mode, $const, $log_mode, $tz_name, $tz_offset, $tz_dst, $has_tz_dst, $user_tz_offset, $old_version_request, $iframe_call, $primary_id, $last_query, $translate;
$db = new MysqliDB(db_host, db_user, db_pass, db_name);
$gh = new SUPPORT();
$const = new ProjectConst();

$operation = $gh->read("op", "");
$token = $gh->read("token", "");
$user_id = $gh->read("user_id", 0);
$company_id = $gh->read("company_id", 0);
$from = $gh->read("from", PANEL_CONSTANT); // web/panel, ios, android, qbd
$version = $gh->read("version", "0.0.0"); // 0.0.0
$request_from_old_version = false;

$request_version = $gh->read('version', '0.00');
// Adding to fix the callback from Shortcut of app.
if ($request_version == '') {
	$request_version = "web";
}

$request_from_web = $request_version == "web";
$request_from = $gh->read('from', '0.00');

// if ($token !== "") {
// 	if (!valid_token($company_id, $user_id, $token)) {
// 		$outputjson['message'] = "Invalid token. please login again.";
// 		$outputjson['success'] = 0;
// 		$response_string = json_encode(($outputjson), JSON_PRETTY_PRINT);
// 		echo $response_string;
// 		return;
// 	}
// }

$loggedin_user = [];
$md5_user_id = 0;
if ($token !== "") {
	$explode_token = explode('_', $token);
	$md5_user_id = $explode_token[1];

	$loggedin_userData = getUsersDetails($md5_user_id, true);
	if ($loggedin_userData) {
		$loggedin_user = $loggedin_userData;
	} else {
		$outputjson['message'] = "Invalid Token!";
		$outputjson['status'] = -2;
		// $outputjson['data'] = (object)[];
		$response_string = json_encode(($outputjson), JSON_PRETTY_PRINT);
		echo $response_string;
		return;
	}
}
// elseif (!in_array($operation, $login_not_require_operation)) {
// 	$outputjson['message'] = "Token not Found.";
// 	$outputjson['status'] = -2;
// 	// $outputjson['data'] = (object)[];
// 	$response_string = json_encode(($outputjson), JSON_PRETTY_PRINT);
// 	echo $response_string;
// 	return;
// }

function generate_token($company_id, $user_id, $salt = null)
{
	$data = $company_id . "___" . $user_id;
	$hash_code = hash_hmac('sha256', $data, 'c45aa71tddaa1415c9caadf2dd8a38b1', false);
	return $hash_code;
}

function valid_token($company_id, $user_id, $token)
{
	if ($token != "" && $user_id > 0 && $company_id > 0) {
		$expected_token = generate_token($company_id, $user_id);
		if (md5($token) === md5($expected_token)) {
			// valid token.. let request proceed
			return true;
		} else {
			// invali token.. stop user here..
			return false;
		}
	}
	return false;
}
if (isset($_POST) && count($_POST) > 0) {
	foreach ($_POST as $post_key => &$post_value) {
		if (is_string($post_value) && $post_key != 'pixel') {
			$post_value = strip_tags($post_value);
		}
	}
}

$debug_mode = $gh->read("debug", DEBUG_MODE);
$log_mode = $gh->read("log", LOG_MODE);
$iframe_call = $gh->read("iframe_call", 0); // 0 or 1
$primary_id = $gh->read("primary_id", 0); // ID of each module
$json_feed = $gh->read("json_feed");
$last_query = "";

$handler = function (\Throwable $ex) {
	global $gh;
	$msg = "[ {$ex->getCode()} ] {$ex->getTraceAsString()}";
	$error = "Service Error: " . $ex->getMessage() . PHP_EOL . $msg;
	//file_put_contents("upload/_log/service_error.txt", print_r($error, true), FILE_APPEND | LOCK_EX);
	$gh->Log($error);

	$outputjson['message'] = "Something went wrong. This issue has been reported. Please try again.";
	//Seema(2020-07-15): Commented below line as it does not reset save buttons from showing loading text as this key stop the execution at response rather than sending data back to callback
	//$outputjson['error'] = "Something went wrong. This issue has been reported. Please try again.";
	$outputjson['success'] = 0;
	$outputjson['data'] = [];

	$response_string = json_encode(($outputjson), JSON_PRETTY_PRINT);
	echo $response_string;
	return;
};
set_exception_handler($handler);


function ServiceErrorHandler(int $errNo, string $errMsg, string $file, int $line)
{

	global $gh;
	if ($errMsg == "mkdir(): File exists") {
		// nothing to do. just ingore it.
	} else {
		$gh->Log(__FUNCTION__ . " Error: #[$errNo] occurred in [$file] at line [$line]: [$errMsg]");
	}
}
set_error_handler('ServiceErrorHandler');

function fatalErrorHandler()
{
	// Let's get last error that was fatal.
	$error = error_get_last();

	// This is error-only handler for example purposes; no error means that
	// there were no error and shutdown was proper. Also ensure it will handle
	// only fatal errors.
	if (null === $error || E_ERROR != $error['type']) {
		return;
	}

	// Log last error to a log file.
	// let's naively assume that logs are in the folder inside the app folder.
	$logFile = fopen("upload/_log/fatal_error.txt", "a+");

	// Get useful info out of error.
	$type    = $error["type"];
	$file    = $error["file"];
	$line    = $error["line"];
	$message = $error["message"];

	fprintf(
		$logFile,
		"[%s] %s: %s in %s:%d\n",
		date("Y-m-d H:i:s"),
		$type,
		$message,
		$file,
		$line
	);

	fclose($logFile);
}
register_shutdown_function('fatalErrorHandler');

$tz_name = $gh->read("tzid", "UTC");
$tz_name = str_replace(" ", "+", $tz_name); //  here if timezone is Etc/GMT+8  then $gh->read() removes the "+" sign. which creates an issue.
$tz_offset = $gh->read("tz", "+00:00");
$curr_time = $gh->read("curr_time", "now");
if (empty($tz_name)) {
	$tz_name = "UTC";
}
$timezone = new DateTimeZone($tz_name);
$tz_offset = $timezone->getOffset(new DateTime);

$seconds = $tz_offset;
$H = floor($seconds / 3600);
$i = ($seconds / 60) % 60;
if ($H < 0) {
	$tz_offset = sprintf("%03d:%02d", $H, $i);
} else {
	$tz_offset = sprintf("%02d:%02d", $H, $i);
}
$tz_offset = ($seconds > 0 ? "+" : "") . $tz_offset;
$tz_offset = $tz_offset == "00:00" ? "+00:00" : $tz_offset;

$date = new DateTime("now", new DateTimeZone($tz_name));
//If we use now, it will check whether DST is on for the now or not. Revised with a date which falls into DST for both Australia and US.
$tz_date = new DateTime("2019-10-06T05:00:00", new DateTimeZone($tz_name));

$tz_dst = $date->format("I"); // daylight saving in timezone..  returns 1 if ON or 0 if OFF (not applicable)
$has_tz_dst = $tz_date->format("I");
$date2 = new DateTime($curr_time, new DateTimeZone($tz_name));
$diffInSeconds = $date2->getTimestamp() - $date->getTimestamp();
$user_tz_offset = $diffInSeconds;

$request_string = "";
if ($log_mode >= 1) {
	$request = array();
	$request = $request + array("QUERY_STRING" => $_SERVER['QUERY_STRING']) + array("IP_ADDRESS" => $gh->get_client_ip());
	if (!empty($_POST) && count($_POST) > 0) {
		$request = $request + $_POST;
	}
	if (!empty(file_get_contents('php://input'))) {
		//$request = $request + ((array)(file_get_contents('php://input')));
	}
	$gh->Log($request);
}

$request_version = $gh->read('version', '0.00');
// Adding to fix the callback from Shortcut of app.
if ($request_version == '') {
	$request_version = "web";
}

$request_from_web = $request_version == "web";
$request_from = $gh->read('from', '0.00');
$live_version = "0.0";
$request_from_old_version = false;
$old_version_request = 0;

if (!is_numeric($user_id)) {
	$outputjson['message'] = "Invalid User ID. Must be an integer.";
	$outputjson['success'] = 0;
	$response_string = json_encode($outputjson, JSON_PRETTY_PRINT);
	echo $response_string;
	return;
} else {
	if ($user_id > 0 && $user_id != '') {
		$user = $db->execute("SELECT usr.* FROM `tbl_users` as usr WHERE usr.id = " . $user_id . " LIMIT 0,1");
		if (count($user) > 0) {
			$userObj = $user[0];
			$gh->current_user = $userObj;
			// if ($userObj['is_deleted'] && $from != "admin") {
			// 	$outputjson['message'] = $const->userDisableModeMessage;
			// 	$outputjson['success'] = 0;
			// 	$outputjson['redirect_to_login'] = 1;
			// 	$response_string = json_encode($outputjson, JSON_PRETTY_PRINT);
			// 	echo $response_string;
			// 	return;
			// }
		}
	}

	if ($debug_mode >= 1) {
		$outputjson["query_info"] = array();
	}

	try {
		if (!isset($operation) || empty($operation)) {
			$outputjson['error'] = "Operation missing in request.";
		} else if (file_exists($operation . ".php")) {
			include($operation . ".php");
			if (is_callable($operation)) {
				//echo "<pre>"; // must be here for formatted output
				$module_key = (isset($_REQUEST['module_key'])) ? $_REQUEST['module_key'] : '';
				$primary_key = (isset($_REQUEST['primary_key'])) ? $_REQUEST['primary_key'] : '';
				$op = (isset($_REQUEST['op'])) ? $_REQUEST['op'] : '';

				$params = $_REQUEST;
				$operation($params);

				/***AUDIT LOG START****/
				$str_arr = explode("_", $operation);

				if (
					$str_arr[0] == 'add'
					|| $str_arr[0] == 'save'
					|| $str_arr[0] == 'update'
					|| $str_arr[0] == 'delete'
					|| $str_arr[0] == 'login'
					|| $str_arr[0] == 'pdf'
				) {
					include("log_manage.php");
					log_manage($params, $outputjson, $operation);
				}
				/***AUDIT LOG OVER****/
			} else {
				$outputjson['error'] = "Operation does not exists";
			}
		} else {
			$outputjson['error'] = "file does not exist";
		}
	} catch (Exception $e) {
		$gh->Log($e->getMessage());
	}
}

if ($log_mode == 2 || $debug_mode >= 1) {
	// append at top of the array..  alternate to array_unshift()
	$outputjson = array("__REQUEST__" => $request) + $outputjson;
}
$temp_outputjson = ($outputjson);
if (empty($json_feed)) {
	$outputjson["date_now"] = date('Y-m-d H:i:s');
	$temp_outputjson = stripslashes_recursively($temp_outputjson);

	$stop_service = microtime(true);
	$time_diff = ($stop_service - $start_service) . ' seconds';
	if (count($outputjson) > 0) {
		$temp_outputjson = array("__service_time__" => $time_diff, "__offset_seconds__" => $user_tz_offset) + $temp_outputjson;
	}
}

if (empty($json_feed)) {
	$response_string = json_encode(($temp_outputjson), JSON_INVALID_UTF8_IGNORE | JSON_PRETTY_PRINT);
} else {
	$response_string = json_encode(($temp_outputjson['data']));
}
$response_string = str_replace('&apos;', "'", $response_string);
if ($log_mode == 2) {
	$gh->Log($response_string);
}

$response_string = str_replace('\r\n', "", $response_string);
$response_string = str_replace('\/', "/", $response_string);
echo $response_string;

function getUsersDetails($id, $is_md5)
{
	global $db;
	if ($is_md5) {
		$query_user = "SELECT usr.*	FROM tbl_users as usr WHERE md5(id) = '$id'";
		$rows = $db->execute($query_user);
	} else {
		$query_user = "SELECT usr.*	FROM tbl_users as usr WHERE id = $id";
		$rows = $db->execute($query_user);
	}
	if ($rows != null && is_array($rows) && count($rows) > 0) {
		return $rows[0];
	} else {
		return null;
	}
}

function stripslashes_recursively($value)
{
	// echo $value."+++";
	if ($value) {
		$value = is_array($value) ?	array_map('stripslashes_recursively', $value) : (isJson($value) ? $value : stripslashes($value));
	}
	return $value;
}

function htmlspecialchars_decode_recursively($value)
{
	$value = is_array($value) ?	array_map('htmlspecialchars_decode_recursively', $value) : htmlspecialchars_decode($value);
	return $value;
}

function recursive_ksort(&$array)
{
	foreach ($array as &$value) {
		if (is_array($value)) recursive_ksort($value);
	}
	return ksort($array);
}

function trim_value($value)
{
	if (is_string($value)) {
		$value = trim($value);    // this removes whitespace and related characters from the beginning and end of the string
	}
}

function isJson($string)
{
	if ($string && strpos($string, "[") === 0) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	} else {
		return false;
	}
}


function fatal_error_handler($buffer)
{
	$error = error_get_last();
	if ($error != null) {
		// type, message, file, line
		$newBuffer = '
				<html><header><title>Fatal Error </title></header>
				<style>
				.error_content{
					background: ghostwhite;
					vertical-align: middle;
					margin:0 auto;
					padding:10px;
					width:50%;
				 }
				 .error_content label{color: red;font-family: Georgia;font-size: 16pt;font-weight: bold;}
				 .error_content ul li{ background: none repeat scroll 0 0 FloralWhite;
							border: 1px solid AliceBlue;
							display: block;
							font-family: monospace;
							padding: 2%;
							text-align: left;
				  }
				</style>
				<body style="text-align: center;">
				  <div class="error_content">
					  <label >Error </label>
					  <ul>
						<li><b>Type</b> ' . FriendlyErrorType($error['type']) . '</li>
						<li><b>Line</b> ' . $error['line'] . '</li>
						<li><b>Message</b> ' . $error['message'] . '</li>
						<li><b>File</b> ' . str_replace(dirname(dirname($error['file'])), "", $error['file']) . '</li>
					  </ul>
				  </div>
				</body>
			  </html>
			';
		return $newBuffer;
	}
	return $buffer;
}

function FriendlyErrorType($type)
{
	switch ($type) {
		case E_ERROR: // 1 //
			return 'E_ERROR';
		case E_WARNING: // 2 //
			return 'E_WARNING';
		case E_PARSE: // 4 //
			return 'E_PARSE';
		case E_NOTICE: // 8 //
			return 'E_NOTICE';
		case E_CORE_ERROR: // 16 //
			return 'E_CORE_ERROR';
		case E_CORE_WARNING: // 32 //
			return 'E_CORE_WARNING';
		case E_COMPILE_ERROR: // 64 //
			return 'E_COMPILE_ERROR';
		case E_COMPILE_WARNING: // 128 //
			return 'E_COMPILE_WARNING';
		case E_USER_ERROR: // 256 //
			return 'E_USER_ERROR';
		case E_USER_WARNING: // 512 //
			return 'E_USER_WARNING';
		case E_USER_NOTICE: // 1024 //
			return 'E_USER_NOTICE';
		case E_STRICT: // 2048 //
			return 'E_STRICT';
		case E_RECOVERABLE_ERROR: // 4096 //
			return 'E_RECOVERABLE_ERROR';
		case E_DEPRECATED: // 8192 //
			return 'E_DEPRECATED';
		case E_USER_DEPRECATED: // 16384 //
			return 'E_USER_DEPRECATED';
	}
	return "";
}

function utf8ize($d)
{
	if (is_array($d)) {
		foreach ($d as $k => $v) {
			$d[$k] = utf8ize($v);
		}
	} else if (is_string($d)) {
		return utf8_encode($d);
	}
	return $d;
}

interface MyPackageThrowable extends Throwable
{
}

class MyPackageException extends Exception implements MyPackageThrowable
{
}
