<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnJson("Only POST method allowed", 405);
	}
	if (!isset($_SERVER["HTTP_TOKEN"])) {
		returnJson("Token missing", 400);
	}
	if (!$session = $db->sessions->where("token", $_SERVER["HTTP_TOKEN"])) {
		returnJson("Token does not exist", 400);
	}
	if ($session->isDisabled){
		returnJson("Token disabled", 403);
	}
	if (!password_verify($_SERVER["REMOTE_ADDR"], $session->ipHash)) {
		returnJson("Invalid token", 403);
	}
	if (!password_verify($_SERVER["HTTP_USER_AGENT"], $session->clientHash)) {
		returnJson("Invalid token", 403);
	}
	
	$user = $db->users->get($session->user);
	$user->lastAccessedOn = date("Y-m-d H:i:s");
	$db->users->update($user);
	
	$session->lastAccessedOn = date("Y-m-d H:i:s");
	$db->sessions->update($session);
	
	returnJson("");