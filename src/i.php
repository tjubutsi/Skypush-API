<?php 
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	$image = $db->uploads->get($_GET["fileId"]);
	if (!$image) {
		returnPage("Image does not exist", 404);
	}
	if ($image->isPrivate) {
		session_start();
		if (!isset($_SESSION['userEmail'])) {
			returnPage("Not authorised", 401);
		}
		else
		{
			$accessToken = $db->accessTokens->get($image->accessToken);
			$session = $db->sessions->get($accessToken->session);
			
			if ($session->user !== $_SESSION['userId']) {
				returnPage("Not authorised", 401);
			}
		}
	}
	header("Content-Type: image/png");
	readfile("images/" . $image->file);
