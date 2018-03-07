<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnJson("Only POST method allowed", 405);
	}
		
	$anonymous = true;
	
	if (isset($_SERVER["HTTP_TOKEN"])) {
		$anonymous = false;
		if (!$accessToken = $db->accessTokens->where("token", $_SERVER["HTTP_TOKEN"])) {
			returnJson("Token invalid", 400);
		}
		if ($accessToken->isDisabled){
			returnJson("Token is disabled", 401);
		}
		if ($accessToken->validUntill < date("Y-m-d H:i:s")){
			returnJson("Token is expired", 401);
		}
	}
	
	$uploadDirectory = "i/";
	chdir($_SERVER["DOCUMENT_ROOT"]);
	$id = base_convert(microtime(true) * 10000, 10, 36);
	$file = $uploadDirectory . $id . ".png";
	$thumb = $uploadDirectory . "t_" . $id . ".png";

	if (!move_uploaded_file($_FILES["data"]["tmp_name"], $file)) {
		returnJson("Upload failed", 500);
	}
	if (!createThumbnail($file, $thumb, 200, 200)) {
		returnJson("Upload failed", 500);
	}
	
	$upload = new upload();
	$upload->file = $id . ".png";
	if (!$anonymous)
	{
		$upload->accessToken = $accessToken->id;
	}
	
	$privateUpload = false;
	
	if (isset($_POST["privateUpload"])) {
		$privateUpload = $_POST["privateUpload"];
	}
	
	$upload->isPrivate = $privateUpload;
	$db->uploads->add($upload);
	
	if (!$anonymous)
	{
		$accessToken->lastAccessedOn = date("Y-m-d H:i:s");
		$accessToken->validUntill = date("Y-m-d H:i:s", strtotime("+1 hours"));
		$db->accessTokens->update($accessToken);
		
		$session = $db->sessions->get($accessToken->session);
		$session->lastAccessedOn = date("Y-m-d H:i:s");
		$db->sessions->update($session);
		
		$user = $db->users->get($session->user);
		$user->lastAccessedOn = date("Y-m-d H:i:s");
		$db->users->update($user);
	
		$client = $db->clients->get($session->client);
		$client->lastAccessedOn = date("Y-m-d H:i:s");
		$db->clients->update($client);
	}
	
	returnJson("https://skyweb.nu/$file");
	
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