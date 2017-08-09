<?php
	include "helpers.php";
	include "loginFunctions.php";
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnResult("Only POST method allowed", 405);
	}
	
	if (!isset($_SERVER["HTTP_TOKEN"])) {
		returnResult("Token missing", 400);
	}
	if (!$session = getSessionByToken($_SERVER["HTTP_TOKEN"], $databaseConnection)){
		returnResult("Token invalid", 400);
	}
	if ($session->isDisabled){
		returnResult("Token is disabled", 403);
	}
	
	$combinedIp = $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_X_FORWARDED_FOR'];
	if (!password_verify($combinedIp, $session->ipHash)) {
		returnResult("Token invalid", 403);
	}
	
	$accessToken = getAccessToken($session);
	returnResult($accessToken);