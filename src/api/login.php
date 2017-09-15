<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		//writeLogEntry("GKUHFRE", logLevel::$notification, $databaseConnection);
		returnJson("Only POST method allowed", 405);
	}
	
	if (!isset($_SERVER["HTTP_TOKEN"])) {
		returnJson("Token missing", 400);
	}
	if (!$client = $db->clients->where("token", $_SERVER["HTTP_TOKEN"])) {
		returnJson("Token invalid", 400);
	}
	if (!isset($_SERVER["PHP_AUTH_USER"])) {
		returnJson("User missing", 400);
	}
	if (!isset($_SERVER["PHP_AUTH_PW"])) {
		returnJson("Password missing", 400);
	}
	if (!$user = $db->users->where("email", $_SERVER["PHP_AUTH_USER"])) {
		returnJson("User does not exist", 400);
	}
	if ($user->isDisabled) {
		returnJson("User is disabled", 401);
	}
	if(!password_verify($_SERVER["PHP_AUTH_PW"], $user->password)){
		$user->loginTries = $user->loginTries + 1;
		if ($user->loginTries >= 3) {
			$user->isDisabled = 1;
		}
		$db->users->update($user);
		returnJson("Wrong password", 401);
	}
	
	$user->loginTries = 0;
	$user->lastAccessedOn = date("Y-m-d H:i:s");
	$db->users->update($user);
	
	$client->lastAccessedOn = date("Y-m-d H:i:s");
	$db->clients->update($client);
	
	$session = new session();
	$session->token = bin2hex(random_bytes(16));
	$session->user = $user->id;
	$session->client = $client->id;
	$session->ipHash = password_hash($_SERVER["REMOTE_ADDR"], PASSWORD_DEFAULT);
	
	$db->sessions->add($session);
	returnJson($session->token);