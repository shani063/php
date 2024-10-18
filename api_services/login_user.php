<?php

function login_user()
{
	global $outputjson, $gh, $db, $const,$tz_name, $tz_offset, $phone_format;
	$outputjson['success'] = 0;

	$username = $gh->read("email");
	$username = addslashes(str_replace('&apos;', "'", $username));
	$password = $gh->read("password");
	$cookie_id = $gh->read("cookie_id", null);
    $dateNow = date('Y-m-d H:i:s');
    $from = $gh->read('from', PANEL_CONSTANT);

    $where = " ";


    if ($cookie_id != null)
	{
		$user_id = $gh->decrypt(rawurldecode($cookie_id));
		if($user_id > 0){
			$where = "( usr.id = '" . $user_id . "') ";
		}
		else{
			$cookie_id = null;
		}
	} 

	if (is_null($cookie_id))
	{ 
		if(empty($username)){
			$outputjson['message'] = "Username is required.";
			return;
		}
		if(empty($password)){
			$outputjson['message'] = "Password is required.";
			return;
		}

		$user_id = 0;
		$where = "( usr.username ='" . $username . "' ) ";
	}

    $query_user = "SELECT usr.* FROM tbl_users as usr WHERE " . $where."";
    $rows = $db->execute($query_user);
    
    if ($rows != null && is_array($rows) && count($rows) > 0) {
		$user = $rows[0];
        $userPassword = $user['password'];
        
        // remove password from user object
        unset($user["password"]);
        
		if($userPassword == $password || $user_id > 0) {
            if($user['is_deleted'] == 1){
                $outputjson['message'] = $const->userDisableModeMessage;
                return;
            }

			$role_id = ($user['role_id'] == '') ? -1 : $user['role_id'];
			
			// disable the last login update when login in from Admin. so we can have the real last login dates.
            $update = array();
            $update['last_logged_in'] = $dateNow;
            if(!empty($gh->read('tzid',''))){

                $timezone = new DateTimeZone($tz_name);
                $tz_offset1 = $timezone->getOffset(new DateTime);
                $seconds = $tz_offset1;
                $offset = ($seconds/60) + ($seconds%60);
                $update['last_login_offset'] = $offset;
            }
            if(count($update) > 0){
                $db->update("tbl_users", $update, array("user_id" => $user["user_id"]));
            }

            $outputjson['success'] = 1;
            $outputjson['global_search_flag'] = 1;
			$outputjson['message'] = 'User logged in successfully.';
            $outputjson["data"] = $user;
		}
		else{
			$outputjson['message'] = "Invalid password. Try again or use Forgot Password. If you are an employee and do not have an email associated with your account, contact your Account Administrator.";
		}
	}
	else {
		$outputjson['message'] = "Your account is Inactive or this Username does not exist. Please try again or contact support@sailons.com.";
	}

}

?>