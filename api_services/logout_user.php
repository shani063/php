<?php
function logout_user()
{
	global $outputjson, $gh, $db, $const,$tz_name,$tz_offset,$phone_format;
	
    $data = array();
    $data['company_id'] = $gh->read('original_company_id',0);
    $data['user_id'] = $user_id = $gh->read('original_user_id',0);
    $data['action'] = "Logout";
    $data['date_added'] = date("Y-m-d H:i:s");
    $data['date_modified'] = date("Y-m-d H:i:s");
    $data['status'] = 1;
    $data['record_id'] = 0;
    $data['module_id'] = 0;
    $data['operation'] = 'logout_user';
    $data['from'] = $gh->read("from",PANEL_CONSTANT);
    $token_id = $gh->read('token_id',0);
    $force_login=$gh->read('force_login',0);

    if($force_login == 0){
        $log_id = $db->insert("tbl_audit_logs", $data);
    }
    

    if(!empty($token_id)){
        $db->delete('tbl_user_device_tokens', array('user_id' => $user_id, 'token_id' => $token_id));
    }

    $outputjson['success'] = 1;
}

?>