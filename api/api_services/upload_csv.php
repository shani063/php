<?php

function upload_csv()
{
	global $outputjson, $gh, $db, $const, $tz_name, $tz_offset, $phone_format;
	$outputjson['success'] = 0;

	$id = $gh->read("id");
	$dateNow = date('Y-m-d H:i:s');
	$from = $gh->read('from', PANEL_CONSTANT);
	$target_dir = "upload/csv/";
	$target_file = $target_dir . date("Y_m_d_h_i_s_") . basename($_FILES["file"]["name"]);
	$uploadOk = 1;

	if (isset($_FILES['file'])) {

		$message = "";
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

		// Allow certain file formats
		if ($imageFileType != "csv") {
			$message = "Sorry, only CSV file is allowed.";
			$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			$outputjson['message'] = $message;
		} else {
			move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
			if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
				// Open the CSV file and read its contents
				$handle = fopen($target_file, "r");
				if ($handle !== FALSE) {
					// Loop through each row of the CSV file
					$is_header = 1;
					$i = 1;
					$last_item = array();
					while (($data = fgetcsv($handle, 40000, ",")) !== FALSE) {
						if ($is_header == 0) {
							$name = $data[0];
							$color = $data[1];
							$size = $data[2];
							$storage = $data[3];
							$selling_price = $data[4];
							$mrp = $data[5];
							$fetaures = $data[6];
							$img1 = $data[7];
							$img2 = $data[8];
							$img3 = $data[9];
							$img4 = $data[10];
							$img5 = $data[11];

							if ($name != "" || $color != "" || $size != "" || $storage != "") {
								// echo '# ' . $name . '-' . $last_item['name'] . '-' . $color . '-' . $storage;
								$name = ($name == '' || $name == '-') ? $last_item['name'] : $name;
								if (count($last_item) !== 0 && $name != $last_item['name']) {
									$last_item = array();
								}
								if (count($last_item) !== 0) {
									// echo '~name ' . $last_item['name'];
									$name = ($name == '' || $name == '-') ? $last_item['name'] : $name;
									$color = ($color == '' || $color == '-') ? $last_item['color'] : $color;
									$size = ($size == '' || $size == '-') ? $last_item['size'] : $size;
									$storage = ($storage == '' || $storage == '-') ? $last_item['storage'] : $storage;
									$selling_price = ($selling_price == '' || $selling_price == '-') ? $last_item['selling_price'] : $selling_price;
									$mrp = ($mrp == '' || $mrp == '-') ? $last_item['mrp'] : $mrp;
									$fetaures = ($fetaures == '' || $fetaures == '-') ? $last_item['fetaures'] : $fetaures;
									$img1 = ($img1 == '' || $img1 == '-') ? $last_item['img1'] : $img1;
									$img2 = ($img2 == '' || $img2 == '-') ? $last_item['img2'] : $img2;
									$img3 = ($img3 == '' || $img3 == '-') ? $last_item['img3'] : $img3;
									$img4 = ($img4 == '' || $img4 == '-') ? $last_item['img4'] : $img4;
									$img5 = ($img5 == '' || $img5 == '-') ? $last_item['img5'] : $img5;
								}
								// echo $i . '. ' . $name;

								$last_item = array(
									"name" => $name,
									"color" => $color,
									"size" => $size,
									"storage" => $storage,
									"selling_price" => $selling_price,
									"mrp" => $mrp,
									"fetaures" => $fetaures,
									"img1" => $img1,
									"img2" => $img2,
									"img3" => $img3,
									"img4" => $img4,
									"img5" => $img5,
									"from_csv" => 1,
								);
								// Shipping Line
								$qry_product = "SELECT * FROM tbl_product WHERE name = '$name'";
								$rows_product = $db->execute($qry_product);
								if ($rows_product != null && is_array($rows_product) && count($rows_product) > 0) {
									$product_id = $rows_product[0]['id'];
								} else {
									$data = $last_item;
									$product_id = $db->insert("tbl_product", $data);
								}

								// Port rates
								$qry_port_chk = "SELECT * FROM tbl_product_verient WHERE product_id = '$product_id' AND color = '$color' AND size = '$size' AND storage = '$storage'";
								$rows_port_chk = $db->execute($qry_port_chk);
								if ($rows_port_chk != null && is_array($rows_port_chk) && count($rows_port_chk) > 0) {
									//Data already Exist
								} else {
									$data = array(
										"product_id" => $product_id,
										"name" => $name,
										"color" => $color,
										"size" => $size,
										"storage" => $storage,
										"selling_price" => $selling_price,
										"mrp" => $mrp,
										"fetaures" => $fetaures,
										"img1" => $img1,
										"img2" => $img2,
										"img3" => $img3,
										"img4" => $img4,
										"img5" => $img5,
										"from_csv" => 1,
									);
									$db->insert("tbl_product_verient", $data);
								}
							}
							$i++;
						}
						$is_header = 0;
					}
					fclose($handle);
					$outputjson['success'] = 1;
					$outputjson['message'] = 'Data inserted Successfully.';
				} else {
					$outputjson['message'] = "File not open!";
				}
			} else {
				$outputjson['message'] = "File not Found!";
			}
		}
	} else {
		$outputjson['message'] = "Please select file to upload!";
	}
}
