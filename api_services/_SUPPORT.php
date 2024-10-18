<?php
//error_reporting(0);
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

global $debug_mode, $db, $global_postmark_array, $postmark_message_id, $log_mode;

require_once '_SimpleImage.php';

// Include composer autoloader
require __DIR__ . '/vendor/autoload.php';

use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//For Mail Sent Using SMTP
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Use the REST API Client to make requests to the Twilio REST API
class CacheHelper {

	// https://www.php.net/manual/en/language.oop5.static.php#101350

	protected static $InstanceCache = null;
	protected static $valid = false;

	/**
	 * Prevent direct object creation
	 */
	final private function  __construct() { }

	/**
	 * Prevent object cloning
	 */
	// final private function  __clone() { }

	/**
	 * Returns new or existing Singleton instance
	 * @return Singleton
	 */
	final public static function GetInstance(){

		if(null !== static::$InstanceCache && static::$valid === 2){
			return static::$InstanceCache;
		}
		static::$InstanceCache = new static();

		$path = __DIR__ . "/upload/";
		if (IS_PRODUCTION == true || IS_SANDBOX == true || IS_BETA == true) {
			$parent_dir1 = dirname(__FILE__); // /var/www/html/production/admin_panel
			$parent_dir2 = dirname(dirname(dirname(__FILE__))); // /var/www/html
			$parent_dir = str_replace($parent_dir2, "", $parent_dir1); //  /production/admin_panel
			//$this->Log("parent_dir = ".$parent_dir);

			$environment = IS_PRODUCTION == true ? "production" : (IS_BETA == true ? "beta" : "sandbox");

			// https://github.com/PHPSocialNetwork/phpfastcache/issues/614#issuecomment-600010179
			// change path to dedicated cache directory..  /tmp should be for temporary usage only.
			$path = "/www/".$environment."/logs/tmp/";

			//if (!is_dir($path)) mkdir($path, 0777, true);
			if (!is_dir($path) && !file_exists($path)) {
				$oldmask = umask(0);
				mkdir($path, 0777, true);
				umask($oldmask);
			}
		}

		CacheManager::setDefaultConfig(new ConfigurationOption([
			'path' => $path, // or in windows "C:/tmp/"
		]));

		// In your class, function, you can call the Cache
		static::$InstanceCache = CacheManager::getInstance('files');

		static::$valid = 2;

		return static::$InstanceCache;
	}

}

class SUPPORT
{
	public $current_user;
	public $InstanceCache;

	
	public function get($input, $trim_special_characters = false)
	{
		$output = $input;
		$output = html_entity_decode($input, ENT_QUOTES | ENT_HTML5);
		if ($trim_special_characters) {
			$output = preg_replace("/[:,?;]/", '', $output);
		}
		return $output;
	}

	public function format_error($errno, $errstr, $errfile, $errline)
	{

		$trace = print_r(debug_backtrace(false), true);
		$content = '<table width="100%" border="1" cellspacing="1" cellpadding="5" style="border:1px solid red"><tr><th>Item</th><th>Description</th></tr><tr><th>Error</th><td><pre>' . $errstr . '</pre></td></tr><tr><th>#</th><td><pre>' . $errno . '</pre></td></tr><tr><th>File</th><td><pre>' . $errfile . '</pre></td></tr><tr><th>Line</th><td><pre>' . $errline . '</pre></td></tr><tr><th>Trace</th><td><pre>' . $trace . '</pre></td></tr></table>';

		return $content;
	}

	public function is_empty($value)
	{
		return (
			!isset($value)
			|| is_null($value)
			|| (!$value && $value != 0)
			|| ($value == "")
		);
	}

	public function read($param_name, $default = null, $input_is_json = false)
	{
		// POST
		if (isset($_POST[$param_name]) && is_array($_POST[$param_name])) {
			return $_POST[$param_name];
		} else if (isset($_POST[$param_name]) && is_string($_POST[$param_name])) {
			$ret = trim($_POST[$param_name]);
			if ($input_is_json == false) {
				$ret = addslashes(rawurldecode($ret));
			}

			return $ret;
		} // REQUEST
		else if (isset($_REQUEST[$param_name]) && is_array($_REQUEST[$param_name])) {
			return $_REQUEST[$param_name];
		} else if (isset($_REQUEST[$param_name]) && is_string($_REQUEST[$param_name])) {
			$ret = trim($_REQUEST[$param_name]);
			$ret = addslashes(urldecode($ret));
			return $ret;
		} else {
			return $default;
		}
		return $ret;
	}

	public function debug($obj, $force_display = false)
	{
		global $debug_mode, $outputjson;
		if ($debug_mode >= 1 || $force_display == true) {
			$outputjson["debug"][] = $obj;
		}
	}

	public function Log($__msg, $sub_path = "", $try_send_email = true)
	{
		//Comment this function because if I some text with word 'error', it goes to infinite loop
		if ($try_send_email) {
			// $this->send_email_if_needed($__msg);
		}

		$msg = "";
		$path = __DIR__ . "/";
		try {
			if (is_array($__msg)) {
				$msg = print_r($__msg, true);
			}

			if(is_object($__msg) && ($__msg instanceof Exception)){
				$msg = $__msg->getMessage();
				$msg .= $__msg->getTraceAsString();
			}
			if(is_string($__msg)){
				$msg = $__msg;
			}

			$requested_by_user_id = $this->read("user_id", "00000");
			$requested_by_user_id = ($requested_by_user_id == "0" || $requested_by_user_id == "") ? "00000" : $requested_by_user_id;
			$requested_by_user_id .= "/";

			$sub_path .= ($sub_path != "") ? "/" : "";

			$month_year = date('Y-m')."/";
			if($month_year == "2021-05/") { $month_year = ""; }
			
			$path1 = $path."upload/_log/" . $month_year;
			$this->TryCreateDirIfNeeded($path1);

			$path1 = $path."upload/_log/" . $month_year . $sub_path;
			$this->TryCreateDirIfNeeded($path1);

			$path1 = $path."upload/_log/" . $month_year . $sub_path . $requested_by_user_id;
			$this->TryCreateDirIfNeeded($path1);

			$msg = str_replace('\r\n', PHP_EOL, $msg);
			$msg = PHP_EOL . date('Y-m-d H:i:s') . ": " . $msg;
			file_put_contents($path1 . date('Y_m_d') . ".txt", $msg, FILE_APPEND | LOCK_EX);
		} catch (Throwable $t) {
			file_put_contents("upload/_log/catch_error.txt", PHP_EOL.date('Y-m-d H:i:s')."  ==>  ".$t->getMessage(), FILE_APPEND | LOCK_EX);
		} catch (Exception $e) {
			file_put_contents("upload/_log/catch_error.txt", PHP_EOL.date('Y-m-d H:i:s')."  ==>  ".$e->getMessage(), FILE_APPEND | LOCK_EX);
		}
	}

	public function TryCreateDirIfNeeded($dirpath, $mode=0777) {

		// is_dir() - Tells whether the filename is a directory
		// file_exists — Checks whether a file or directory exists
		$success = false;

		try {

			if(is_dir($dirpath) && file_exists($dirpath) )
			{
				$success = true;
			}
			else if(!file_exists($dirpath))
			{
				// When we have multiple calls first time (when dir not exist), the process is facing the race condition. so try to sleep the process with randon microseconds. 100000 = 100ms
				usleep(intval(rand(1,100000)));

				$oldmask = umask(0);
				$success = @mkdir($dirpath, $mode, true);
				@chmod($dirpath, $mode);
				umask($oldmask);
			}
		}
		catch(Throwable $th_ex) {
			// ignore
		}
		catch(Exception $ex) {
			// ignore
		}
		finally {
			// not needed for now
		}
		return  $success;
	}

	public function LogQueryStart($query)
	{
		global $debug_mode, $outputjson, $log_mode;
		if ($debug_mode >= 1 || $log_mode >= 3) {
			$query_info = array();

			$query = str_replace('\r\n', " ", $query);
			$query = str_replace("\t", " ", $query);
			$query = str_replace("\r", " ", $query);
			$query = str_replace("\n", " ", $query);
			$query_info["query"] = $query;
			$query_start = microtime(true);
			$outputjson["query_info"][] = $query_info;

			return $query_start;
		}
		return null;
	}

	public function LogQueryResult($query_result)
	{
		global $debug_mode, $outputjson, $log_mode;

		if ($debug_mode <= 1 && $log_mode < 4) {
			return;
		}

		if (is_null($query_result)) {
			return;
		}

		if ($debug_mode >= 1 || $log_mode == 4) {
			$query_info = array_pop($outputjson["query_info"]);
			$query_info["output"] = $query_result;
			$outputjson["query_info"][] = $query_info;
		}

		if($log_mode == 4 && isset($query_info)){
			$this->Log($query_info, "QUERY");
			unset($outputjson["query_info"]);
		}
	}

	public function LogQueryEnd($query_start)
	{
		global $debug_mode, $outputjson, $log_mode;

		if (is_null($query_start)) {
			return;
		}

		if ($debug_mode >= 1 || $log_mode >= 3) {
			$query_stop = microtime(true);
			$query_time_diff = ($query_stop - $query_start) . ' seconds';
			$query_info = array_pop($outputjson["query_info"]);
			$query_info["time"] = $query_time_diff;
			$outputjson["query_info"][] = $query_info;
		}

		if($log_mode == 3 && isset($query_info)){
			$this->Log($query_info, "QUERY");
			unset($outputjson["query_info"]);
		}
	}

	public function encrypt($string_to_encrypt)
	{
		$password = "TheSailonApp";
		$encrypted_string = openssl_encrypt($string_to_encrypt, "AES-128-ECB", $password);
		return $encrypted_string;
	}

	public function decrypt($encrypted_string)
	{
		$encrypted_string = preg_replace('/\s+/', '+', $encrypted_string);
		$password = "TheSailonApp";
		$decrypted_string = openssl_decrypt($encrypted_string, "AES-128-ECB", $password);
		return $decrypted_string;
	}

	public function parse_json_to_array($json)
	{
		$json = htmlspecialchars_decode($json);
		return (array)json_decode($json);
	}

	public function call_service($parameter = array())
	{
		global $tz_offset, $tz_name;
		$result = null;
		$parameter["tz"] = $tz_offset;
		$parameter["tzid"] = $tz_name;
		try {
			$curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_URL, API_SERVICE_URL . "manage.php");
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl_handle, CURLOPT_POST, 1);
			curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $parameter);
			$buffer = curl_exec($curl_handle);
			curl_close($curl_handle);
			$data = $this->removeBOM($buffer);
			$result = json_decode($data, true);

		} catch (Throwable $t) {
			// Executed only in PHP 7, will not match in PHP 5.x
			$this->Log($t);
		} catch (Exception $e) {
			$this->Log($e);
		}
		return $result;
	}

	function removeBOM($data) {
		if (0 === strpos(bin2hex($data), 'efbbbf')) {
			return substr($data, 3);
		}
		return $data;
	}

	/* This Function is used for upload image. When thumb_needed is true is also upload thumbnail image in thumb folder */
	public function UploadImage($file, $thumb_needed, $prepend = "")
	{

		// global $gh;

		$img_path = "";
		$thumb_needed = isset($thumb_needed) ? $thumb_needed : true;

		if (!isset($_FILES[$file]['size']) || $_FILES[$file]['size'] == '' || $_FILES[$file]['size'] <= 0) {
			return $img_path;
		}

		if (isset($_FILES[$file]['name']) && $_FILES[$file]['name'] != '') {
			if ($prepend == "") {
				$prepend = time() . "_";
			}
			$success = move_uploaded_file($_FILES[$file]['tmp_name'], "upload/tmp/" . $_FILES[$file]['name']);
			if ($success) {
				$img_path = API_SERVICE_URL . UPLOAD . 'tmp/' . $_FILES[$file]['name'];
				$this->Log('image uploaded: ' . $img_path);
			}

			if ($success && $thumb_needed == true) {
				$this->GetThumbnail("upload/tmp/" . $_FILES[$file]['name'], "upload/tmp_thumb/" . $_FILES[$file]['name'], 308);
				$img_path = API_SERVICE_URL . UPLOAD . 'tmp_thumb/' . $_FILES[$file]['name'];
				$this->Log('thumb uploaded: ' . $img_path);
			}
		}
		return $img_path;
	}

	/* This function is used for get thumbnail size image */
	public function GetThumbnail($big_img_path, $image_path, $wid)
	{
		$this->debug($image_path . " - " . $wid);
		$image = new SimpleImage();
		$image->load($big_img_path);
		if ($wid > 0 && $wid != $image->getWidth()) {
			$this->debug("Processing $image_path -> $wid");
			$image->resizeToWidth($wid);
			$result = $image->save($image_path);
			$this->debug($result);
			$this->debug("Processing done");
		} else {
			$this->debug("No processing needed for $image_path");
		}
	}

	public function get_client_ip()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		} else if (isset($_SERVER['HTTP_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		} else if (isset($_SERVER['REMOTE_ADDR'])) {
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		} else {
			$ipaddress = 'UNKNOWN';
		}

		return $ipaddress;
	}

	public function download_file($source_url, $destination_file_path)
	{
		try {
			set_time_limit(0);
			//This is the file where we save the    information
			$fp = fopen($destination_file_path, 'w+');
			//Here is the file we are downloading, replace spaces with %20
			$ch = curl_init(str_replace(" ", "%20", $source_url));
			curl_setopt($ch, CURLOPT_TIMEOUT, 50);
			// write curl response to file
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			// get curl response
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);
		} catch (Exception $e) {
			$this->Log($e);
			return false;
		}
		return true;
	}

	public function format_currency($amount)
	{
		$amount = str_replace("_", "", $amount);
		$amount = $amount == "" ? 0.00 : $amount;
		$currency = "USD ";
		$dec_point = ".";
		$thousands_sep = ",";

		$output = $currency . number_format($amount / 100, 2, $dec_point, $thousands_sep);
		return $output;
	}

	public function GetString($input)
	{
		if (isset($input) && $input != null) {
			$input = html_entity_decode($input, ENT_QUOTES | ENT_HTML5);
			return preg_replace('/(?!<[a-zA-Z=\"\':; ]*[^ ]>|<\\/[a-zA-Z="\':; ]*>)(<)/', "&lt;", $input);
		}
		return "";
	}

	public function getFileNameFromURL($fileUrl){
		$fileParts = explode('.',basename($fileUrl));
		unset($fileParts[count($fileParts)-1]);
		$fileParts = implode('.',$fileParts);
		return $fileParts;
	}


	function print_pdf_cell($obj, $key = "", $is_image = false, $cell = 1)
	{
		$output = "";
		if (!is_string($obj) && isset($obj) && isset($key) && isset($obj[$key]) && $obj[$key] != null) {
			$output = stripslashes($this->GetString($obj[$key]));
			if ($output != "" && $is_image) {
				$output = '<img width="130px" src="' . $output . '" alt=""/>';
				return $output;
			}
		}
		if (is_string($obj) || is_int($obj) || is_float($obj)) {
			$output = $this->GetString($obj);
		}

		$needles = array("<br>", "&#13;", "<br/>", "\n");
		$replacement = "<br />";
		$output = str_replace($needles, $replacement, $output);
		$output = str_replace('\"', '"', $output);
		$output = str_replace('\'', "'", $output);
		$output = str_replace('\\\\', '\\', $output);

		return $output;
	}

	function __construct() {    }

	public function read_from_cache_if_exist($key, &$result)
	{
		global $outputjson, $db, $const;
		$is_exist = false;
		try {
			$result = array();
			if (!isset($key) || $key == null || $key == "") {
				return null;
			}

			$CachedString = CacheHelper::GetInstance()->getItem($key);
			if ($CachedString != null && $CachedString->isHit()) {
				$result = $CachedString->get();
				$is_exist = true;
			}
		} catch (Throwable $t) {
			// Executed only in PHP 7, will not match in PHP 5.x
			$this->Log($t);
		} catch (Exception $e) {
			$this->Log($e);
		}
		return $is_exist;
	}

	public function set_data_in_cache($key, $data, $expires_after = null)
	{
		global $outputjson, $db, $const;
		if (!isset($key) || $key == null || $key == "") {
			return null;
		}

		$expires_after = $expires_after == null ? PHPFASTCACHE_EXPIRE_SEC : $expires_after;

		$CachedString = CacheHelper::GetInstance()->getItem($key);
		if (!$CachedString->isHit()) {
			$CachedString->set($data)->expiresAfter($expires_after);//in seconds, also accepts Datetime
			CacheHelper::GetInstance()->save($CachedString); // Save the cache item just like you do with doctrine and entities
		}
		return true;
	}

	public function remove_data_from_cache($keys = array())
	{
		global $outputjson, $db, $const;

		if (!isset($keys) || $keys == null || count($keys) == 0) {
			return null;
		}
		foreach ($keys as $key) {
			set_data_in_cache($key, null);
		}

		//$this->Psr16Adapter->deleteMultiple($keys);
		return true;
	}

	public function echoBase64($filename)
	{
		$contents = file_get_contents($filename);
		$base64_contents = base64_encode($contents);
		$base64_contents_split = str_split($base64_contents, 80);
		$str = '';
		foreach ($base64_contents_split as $one_line) {
			$str .= $one_line;
		}
		return $str;
	}

	public function get_http_response_code($url)
	{
		$headers = get_headers($url);
		return substr($headers[0], 9, 3);
	}

	function encrypt_decrypt($action, $string) {
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'This is my secret key';
		$secret_iv = 'This is my secret iv';
		// hash
		$key = hash('sha256', $secret_key);

		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		if ( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		} else if( $action == 'decrypt' ) {
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}
		return $output;
	}

	function getRealIpAddr() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	// In use for creating filename on Amazon S3 bucket.
	public function remove_special_char($filename){

		// replace special characters except underscore, dash, dot
		$filename = preg_replace("/[^a-z0-9\s\_\-\.]/i", '', $filename);

		// replace multiple space with single
		$filename = preg_replace('!\s+!', ' ', $filename);

		// replace space with underscore
		$filename = str_replace(' ', '_', $filename);

		return $filename;
	}

	function cleanup_enconding($str){

		// for some reason, mb_convert is not working
		// echo mb_convert_encoding($msg_title, 'HTML-ENTITIES', 'UTF-8');

		$str = str_replace("â€™", "'", $str); // left single smart-quote
		$str = str_replace("â€˜", "'", $str); // right single smart-quote
		$str = str_replace("â€œ", "\"", $str); // left double smart-quotation mark
		$str = str_replace("â€", "\"", $str); // right double smart-quotation mark
		return $str;
	}

	//Use this function to get proportional height/width of company logo when attaching it in email.
	function getImageHeightWidthRatio($image, $imgHeight, $imgWidth){
		$final_logo_width = $logo_width = $max_logo_width = 307;
		$final_logo_height = $logo_height = $max_logo_height = 71;
		if(!empty($image)) {
			if (!empty($image)) {
				$company_image = str_replace("/thumb/", "/large/", $image);

				if (empty($imgWidth)) {
					list($logo_width, $logo_height) = getimagesize($company_image);
				} else {
					$logo_width = $imgWidth;
					$logo_height = $imgHeight;
				}

				if ($logo_height > $max_logo_height) {
					$final_logo_height = $max_logo_height;
					$final_logo_width = intval(($logo_width * $final_logo_height) / $logo_height);
				} else {
					$final_logo_height = $logo_height;
					$final_logo_width = intval(($logo_width * $final_logo_height) / $logo_height);
				}
			}
		}
		return array('height' => $final_logo_height, 'width' => $final_logo_width);
	}

	function send_email($toUserEmail,$toUserName,$emailSubject,$emailBody,$ccEmail = array()){
		
		global $outputjson, $db, $const;

		$query_settings = "SELECT * FROM tbl_settings LIMIT 1";
		$settings_rows = $db->execute($query_settings);
		$setting = $settings_rows[0];

		$mail = new PHPMailer(true);

		//Enable SMTP debugging.
		//$mail->SMTPDebug = 3;
		//Set PHPMailer to use SMTP.
		$mail->isSMTP();
		//Set SMTP host name
		$mail->Host = "smtp.gmail.com";
		//Set this to true if SMTP host requires authentication to send email
		$mail->SMTPAuth = true;
		//Provide username and password
		$mail->Username = $setting['admin_email'];
		$mail->Password = $setting['admin_email_password'];
		//If SMTP requires TLS encryption then set it
		// $mail->SMTPSecure = "ssl";
		$mail->SMTPSecure = "tls";
		//Set TCP port to connect to
		$mail->Port = 587;

		$mail->From = $setting['admin_email']; // this needs to be the user's email address
		$mail->FromName = $setting['company_name'];

		$mail->addAddress($toUserEmail, $toUserName);

		if(count($ccEmail) > 0){
			foreach($ccEmail as $recipient){
				$mail->AddCC($recipient,"");
			}
		}

		$mail->isHTML(true);

		$mail->Subject = $emailSubject;
		$mail->Body = $emailBody;

		try {
			$mail->send();
			$smtpMailResponse['success'] = 1;
			$smtpMailResponse['message'] = "Message has been sent successfully";
		} catch (Exception $e) {
			$smtpMailResponse['success'] = 0;
			$smtpMailResponse['message'] = "Mailer Error: " . $mail->ErrorInfo;
		}

		return $smtpMailResponse;
	}
}
