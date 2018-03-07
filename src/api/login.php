<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnJson("Only POST method allowed", 405);
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
	
	$session = new session();
	$session->token = createToken();
	$session->user = $user->id;
	$session->clientHash = password_hash($_SERVER["HTTP_USER_AGENT"], PASSWORD_DEFAULT);
	$session->ipHash = password_hash($_SERVER["REMOTE_ADDR"], PASSWORD_DEFAULT);
	
	$db->sessions->add($session);
	returnJson($session->token);
	
	function createToken() {
		$token = bin2hex(random_bytes(32));
		if ($db->sessions->where("token", $token)) {
			$token = createToken();
		}
		
		return $token;
	}