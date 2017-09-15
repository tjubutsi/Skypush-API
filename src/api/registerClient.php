<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "GET") {
		returnJson("Only GET method allowed", 405);
	}
	
	$client = new client();
	$client->token = bin2hex(random_bytes(16));
	if (!$db->clients->add($client)) {
		returnJson("Registering client failed", 500);
	}
	
	returnJson($client->token);