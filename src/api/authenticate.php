<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnJson("Only POST method allowed", 405);
	}
	
	verifyAuthorization()
	$requestData = json_decode(file_get_contents("php://input"));
	
	returnSuccess();