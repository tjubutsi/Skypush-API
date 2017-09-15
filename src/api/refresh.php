<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
		returnJson("Only PUT method allowed", 405);
	}
	
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
	
	returnJson("Refreshed");