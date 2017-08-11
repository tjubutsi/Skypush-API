<?php
	require_once(dirname(__FILE__) . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnResult("Only POST method allowed", 405);
	}
	
	if (!isset($_SERVER["HTTP_TOKEN"])) {
		returnResult("Token missing", 400);
	}
	if (!$session = $db->sessions->where("token", $_SERVER["HTTP_TOKEN"])) {
		returnResult("Token invalid", 400);
	}
	if ($session->isDisabled){
		returnResult("Token is disabled", 403);
	}
	
	$combinedIp = $_SERVER['REMOTE_ADDR'];
	if (!password_verify($combinedIp, $session->ipHash)) {
		returnResult("Token invalid", 403);
	}
	
	$user = $db->users->get($session->user);
	$user->lastAccessedOn = date("Y-m-d H:i:s");
	$db->users->update($user);
	
	$client = $db->clients->get($session->client);
	$client->lastAccessedOn = date("Y-m-d H:i:s");
	$db->clients->update($client);
	
	$updateableSession = $db->clients->get($session->id);
	$client->lastAccessedOn = date("Y-m-d H:i:s");
	$db->clients->update($client);
	
	$accessToken = new accessToken();
	$accessToken->token = bin2hex(random_bytes(16));
	$accessToken->session = $session->id;
	$db->accessTokens->add($accessToken);
	
	returnResult($accessToken->token);