<?php

function update_product_order()
{
	global $outputjson, $gh, $db;
	$outputjson['success'] = 0;

	$id = $gh->read("id");
	$disp_order = $gh->read("disp_order");

	if ($id != "") {

		$data = array(
			"disp_order" => $disp_order
		);
		$rows = $db->update('tbl_product', $data, array("id" => $id));

		$outputjson['success'] = 1;
		$outputjson['message'] = 'Order updated successfully.';
		$outputjson["data"] = $rows;
	} else {
		$outputjson["data"] = [];
		$outputjson['message'] = "Error!";
	}
}
