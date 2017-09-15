<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/lib/SkyCRUD/src/db.php");
	$db = new db();
	
	function returnJson($message, $code = 200) {
		$data = new stdClass();
		$data->message = $message;
		http_response_code($code);
		echo json_encode($data);
		die();
	}
	
	function returnPage($message, $code = 200) {
		http_response_code($code);
		echo $message;
		die();
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