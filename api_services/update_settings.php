<?php

function update_settings()
{
	global $outputjson, $gh, $db, $const, $tz_name, $tz_offset, $phone_format;
	$outputjson['success'] = 0;

	$cmp_name = $gh->read("cmp_name");
	$cmp_email = $gh->read("cmp_email");
	$admin_email = $gh->read("admin_email");
	$admin_email_password = $gh->read("admin_email_password");
	$contact1 = $gh->read("contact1");
	$contact2 = $gh->read("contact2");
	$address = $gh->read("address");
	$upi = $gh->read("upi");
	$pixel = $_POST["pixel"];
	$status = $gh->read("status");
	$dateNow = date('Y-m-d H:i:s');
	$from = $gh->read('from', PANEL_CONSTANT);


	if ($cmp_name != "" && $cmp_email != "" && $admin_email != "" && $admin_email_password != "" && $contact1 != "" && $contact2 != "" && $address != "") {

		$data = array(
			"company_name" => $cmp_name,
			"company_email" => $cmp_email,
			"admin_email" => $admin_email,
			"admin_email_password" => $admin_email_password,
			"contact1" => $contact1,
			"contact2" => $contact2,
			"address" => $address,
			"upi" => $upi,
			"pixel" => $pixel,
		);
		$rows = $db->update('tbl_settings', $data, array("id" => 1));

		$myfile = fopen("../setting.php", "w") or die("Unable to open file!");
		$upi_txt = '<?php define("UPI_ID", "' . $upi . '"); ?>';
		fwrite($myfile, $upi_txt);
		$upi_script = '<script>var UPI_ID = "' . $upi . '"</script>';
		fwrite($myfile, $upi_script);
		$txt = str_replace("&apos;", "'", $pixel);
		fwrite($myfile, $txt);
		fclose($myfile);

		$outputjson['pixel'] = $pixel;
		$outputjson['success'] = 1;
		$outputjson['message'] = 'Settings updated successfully.';
		$outputjson["data"] = $rows;
	} else {
		$outputjson["data"] = [];
		$outputjson['message'] = "Error!";
	}
}
