<?php

function add_shipping_line()
{
	global $outputjson, $gh, $db, $const,$tz_name, $tz_offset, $phone_format;
	$outputjson['success'] = 0;

	$id = $gh->read("id");
	$source = $gh->read("source");
	$source_name = $gh->read("source_name");
	$container = $gh->read("container");
	$name = $gh->read("name");
    $dateNow = date('Y-m-d H:i:s');
    $from = $gh->read('from', PANEL_CONSTANT);

	if($name){
		if($id > 0)
		{
			$result = $db->update("tbl_shipping_line", array("name"=> $name,"source"=> $source,"source_name"=> $source_name,"container"=> $container), array("id"=>$id));
			$outputjson['message'] = 'data updated successfully.';
		}
		else{
			$result = $db->insert("tbl_shipping_line", array("name"=> $name,"source"=> $source,"source_name"=> $source_name,"container"=> $container));
			$outputjson['message'] = 'data inserted successfully.';
		}
		
		$outputjson['result'] = $result;
		$outputjson['success'] = 1;
	}
	else {
		$outputjson['message'] = "Please add Rates Category!";
	}

}

?>