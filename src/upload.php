<?php
	require_once(dirname(__FILE__) . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnResult("Only POST method allowed", 405);
	}
		
	$anonymous = true;
	
	if (isset($_SERVER["HTTP_TOKEN"])) {
		$anonymous = false;
		if (!$accessToken = $db->accessTokens->where("token", $_SERVER["HTTP_TOKEN"])) {
			returnResult("Token invalid", 400);
		}
		if ($accessToken->isDisabled){
			returnResult("Token is disabled", 403);
		}
		if ($accessToken->validUntill < date("Y-m-d H:i:s")){
			returnResult("Token is expired", 403);
		}
	}
	
	$uploadDirectory = "i/";
	$thumbnailDirectory = $uploadDirectory . "t/";
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
	
	$upload = new upload();
	$upload->file = $file;
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
		$accessToken->validUntill = date("Y-m-d H:i:s", strtotime('+1 hours'));
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
	
	returnResult("https://skyweb.nu/$file");