<?php
	//non functional
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnResult("Only POST method allowed", 405);
	}
	$token = getallheaders()["token"];
	if (strlen($token) == 0) {
		returnResult("Upload failed, invalid token", 401);
	}
	$uploadDirectory = "i/";
	$thumbnailDirectory = $uploadDirectory . "t/";
	error_log(print_r($_FILES["data"]["error"], true));
	chdir($_SERVER["DOCUMENT_ROOT"]);
	$id = base_convert(microtime(true) * 10000, 10, 36);
	$file = $uploadDirectory . $id . ".png";
	$thumb = $thumbnailDirectory . "t_" . $id . ".png";

	if (!move_uploaded_file($_FILES["data"]["tmp_name"], $file)) {
		returnResult("Upload failed", 500);
	}
	if (!createThumbnail($file, $thumb, 200, 200)) {
		returnResult("Upload failed", 500);
	}
	
	$mysqli = new mysqli('127.0.0.1', 'skypush', 'skypush', 'skypush');
	
	if ($mysqli->connect_errno) {
		return false;
	}
	
	$sql = "INSERT INTO uploads (token, file) VALUES('$token', '$file');";
	if ($mysqli->query($sql) === true) {
		returnResult("https://skyweb.nu/$file");
	}
	else {
		returnResult("upload failed", 500);
		die();
	}
	$result->free();
	$mysqli->close();
	
	function insertTokenAndFile($token, $file){
		$mysqli = new mysqli('127.0.0.1', 'skypush', 'skypush', 'skypush');
		
		if ($mysqli->connect_errno) {
			return false;
		}
		
		$sql = "INSERT INTO uploads (token, file) VALUES($token, $file);";
		if ($mysqli->query($sql) === true) {
			return true;
		}
		else {
			$a = "Query: " . $sql . "\n";
			$a += "Errno: " . $mysqli->errno . "\n";
			$a += "Error: " . $mysqli->error . "\n";
			returnResult($a, 500);
			die();
		}
		$result->free();
		$mysqli->close();
	}

	function createThumbnail($filepath, $thumbpath, $thumbnail_width, $thumbnail_height, $background=false) {
		list($original_width, $original_height, $original_type) = getimagesize($filepath);
		if ($original_width > $original_height) {
			$new_width = $thumbnail_width;
			$new_height = intval($original_height * $new_width / $original_width);
		} else {
			$new_height = $thumbnail_height;
			$new_width = intval($original_width * $new_height / $original_height);
		}
		$dest_x = intval(($thumbnail_width - $new_width) / 2);
		$dest_y = intval(($thumbnail_height - $new_height) / 2);
		$old_image = ImageCreateFromPNG($filepath);
		$new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
		imagesavealpha($new_image, TRUE);
		$color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
		imagefill($new_image, 0, 0, $color);
		imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
		ImagePNG($new_image, $thumbpath);
		return file_exists($thumbpath);
	}

	function returnResult($message, $code = 200) {
		$data = new stdClass();
		$data->message = $message;
		http_response_code($code);
		echo json_encode($data);
		die();
	}