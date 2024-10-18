<?php

function delete_record()
{
	global $outputjson, $gh, $db;
	$outputjson['success'] = 0;

	$id = $gh->read("id");
	$type = $gh->read("type");
	$dateNow = date('Y-m-d H:i:s');
	$from = $gh->read('from', PANEL_CONSTANT);

	if ($id > 0 && $type != "") {
		$table = "";
		switch ($type) {
			case 'PRODUCT':
				$db->delete('tbl_product_verient', array("product_id" => $id));
				$db->delete('tbl_product', array("id" => $id));
				break;
			case 'VERIENT':
				$db->delete('tbl_product_verient', array("id" => $id));
				break;
		}

		$outputjson['message'] = 'data deleted successfully.';
		$outputjson['success'] = 1;
	} else {
		$outputjson['message'] = "Sorry, somthing went wrong!";
	}
}
