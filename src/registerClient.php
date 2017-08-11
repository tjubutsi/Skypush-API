<?php
	require_once(dirname(__FILE__) . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "GET") {
		returnResult("Only GET method allowed", 405);
	}
	
	$client = new client();
	$client->token = bin2hex(random_bytes(16));
	if (!$db->clients->add($client)) {
		returnResult("Registering client failed", 500);
	}
	
	returnResult($client->token);