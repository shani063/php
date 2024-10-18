<?php

function get_settings()
{
	global $outputjson, $gh, $db, $const,$tz_name, $tz_offset, $phone_format;
	$outputjson['success'] = 0;

    $from = $gh->read('from', PANEL_CONSTANT);
	$currency = [];
	$rates_category = [];

	$query_port_rates = "SELECT * FROM tbl_settings";
    $rows = $db->execute($query_port_rates);

	$outputjson['success'] = 1;
	$outputjson['message'] = 'success.';
	$outputjson["data"] = $rows[0];
}

?>