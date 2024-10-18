<?php

function get_product_details()
{
	global $outputjson, $gh, $db;
	$outputjson['success'] = 0;
	$outputjson['status'] = 0;
	$id = $gh->read("id");

	$query_port_rates = "SELECT DISTINCT p.*,
		GROUP_CONCAT(DISTINCT pv.size) AS sizes, 
		GROUP_CONCAT(DISTINCT pv.storage) AS storages
		FROM tbl_product as p 
		INNER JOIN `tbl_product_verient` pv ON pv.product_id = p.id
		WHERE MD5(p.id) = '$id'";
	$rows = $db->execute($query_port_rates);

	if ($rows != null && is_array($rows) && count($rows) > 0) {
		$product_id = $rows[0]['id'];
		$query_verient = "SELECT pv.* FROM `tbl_product_verient` pv WHERE pv.product_id = $product_id";
		$rows_verient = $db->execute($query_verient);
		$rows[0]['verients'] = $rows_verient;

		$query_colors = "SELECT DISTINCT pv.color as color_name, pv.img1 as color_img FROM `tbl_product_verient` pv WHERE pv.product_id = $product_id GROUP BY pv.color";
		$rows_colors = $db->execute($query_colors);
		$rows[0]['colors'] = $rows_colors;

		$outputjson['success'] = 1;
		$outputjson['status'] = 1;
		$outputjson['message'] = 'success.';
		$outputjson["data"] = $rows[0];
	} else {
		$outputjson["data"] = [];
		$outputjson['message'] = "No Product found!";
	}
}
