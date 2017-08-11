<?php
	include "includes/helpers.php";
	include "includes/loginFunctions.php";
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		//writeLogEntry("GKUHFRE", logLevel::$notification, $databaseConnection);
		returnResult("Only POST method allowed", 405);
	}
	
	if (!isset($_SERVER["HTTP_TOKEN"])) {
		returnResult("Token missing", 400);
	}
	if (!$clientId = getClientIdByToken($_SERVER["HTTP_TOKEN"], $databaseConnection)){
		returnResult("Token invalid", 400);
	}
	if (!isset($_SERVER["PHP_AUTH_USER"])) {
		returnResult("User missing", 400);
	}
	if (!isset($_SERVER["PHP_AUTH_PW"])) {
		returnResult("Password missing", 400);
	}
	if(!$user = getUserByEmail($_SERVER["PHP_AUTH_USER"], $databaseConnection)){
		returnResult("User does not exist", 400);
	}
	if ($user->isDisabled) {
		returnResult("User is disabled", 401);
	}
	if(!password_verify($_SERVER["PHP_AUTH_PW"], $user->password)){
		processWrongPassword($user, $databaseConnection);
		returnResult("Wrong password", 401);
	}
	
	$combinedIp = $_SERVER['REMOTE_ADDR'];
	$accessToken = processLoginSuccess($user, $clientId, password_hash($combinedIp, PASSWORD_DEFAULT), $databaseConnection);
	returnResult($accessToken);