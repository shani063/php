<?php

function get_products()
{
	global $outputjson, $gh, $db, $const, $tz_name, $tz_offset, $phone_format;
	$outputjson['success'] = 0;
	$outputjson['status'] = 0;

	$start = $gh->read("start");
	$length = $gh->read("length");
	$searcharr = $gh->read("search");
	$search = $searcharr['value'];
	$orderarr = $gh->read("order");
	$orderindex = $orderarr[0]['column'];
	$orderdir = $orderarr[0]['dir'];
	$columnsarr = $gh->read("columns");
	$ordercolumn = $columnsarr[$orderindex]['name'];

	$dateNow = date('Y-m-d H:i:s');
	$from = $gh->read('from', PANEL_CONSTANT);


	$whereData = "(
		p.name LIKE '%" . $search . "%' 
		OR pv.color LIKE '%" . $search . "%' 
		OR pv.size LIKE '%" . $search . "%' 
		OR pv.storage LIKE '%" . $search . "%' 
		OR pv.selling_price LIKE '%" . $search . "%')";

	$total_count = $db->get_row_count('tbl_product', "1=1");
	$filtered_count = 0;
	$count_query = "SELECT count(DISTINCT p.id) as cnt FROM tbl_product as p 
		INNER JOIN `tbl_product_verient` pv ON pv.product_id = p.id
		WHERE " . $whereData;
	$filtered_count = $db->execute_scalar($count_query);

	$orderby = " ORDER BY CASE WHEN (p.disp_order = null OR p.disp_order = 0) THEN 9999999 ELSE p.disp_order END";
	if ($ordercolumn != "") {
		$orderby .= ", " . $ordercolumn . " " . $orderdir;
	}
	$query_port_rates = "SELECT DISTINCT p.*,MD5(p.id) AS md5_id
		FROM tbl_product as p 
		INNER JOIN `tbl_product_verient` pv ON pv.product_id = p.id
		WHERE " . $whereData . " " . $orderby . " LIMIT " . $start . "," . $length . "";
	$rows = $db->execute($query_port_rates);

	if ($rows != null && is_array($rows) && count($rows) > 0) {
		foreach ($rows as $key => $product) {
			$product_id = $product['id'];
			$query_verient = "SELECT pv.*
				FROM `tbl_product_verient` pv WHERE pv.product_id = $product_id";
			$rows_verient = $db->execute($query_verient);
			$rows[$key]['verients'] = $rows_verient;
		}
		$outputjson['recordsTotal'] = $total_count;
		$outputjson['recordsFiltered'] = $filtered_count;
		$outputjson['success'] = 1;
		$outputjson['status'] = 1;
		$outputjson['message'] = 'success.';
		$outputjson["data"] = $rows;
	} else {
		$outputjson["data"] = [];
		$outputjson['recordsTotal'] = $total_count;
		$outputjson['recordsFiltered'] = 0;
		$outputjson['message'] = "No Rates Category found!";
	}
}
