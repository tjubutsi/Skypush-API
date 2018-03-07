<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnJson("Only POST method allowed", 405);
	}
	
	$token = new token();
	$token->token = createToken();
	
	$db->tokens->add($token);
	returnJson($token->token);