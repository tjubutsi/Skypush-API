<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnJson("Only POST method allowed", 405);
	}
	
	verifyAuthorization()
	
	$requestData = json_decode(file_get_contents("php://input"));
	
	if (!isset($requestData->email)) {
		returnError("Email missing", 400);
	}
	if (!filter_var($requestData->email, FILTER_VALIDATE_EMAIL)) {
		returnError("Email is invalid", 400);
	}
	if (!isset($requestData->password)) {
		returnError("Password missing", 400);
	}
	if (!isset($requestData->passwordVerify)) {
		returnError("Password verification missing", 400);
	}
	if (strlen($requestData->password) < 8) {
		returnError("Password needs to be 8 characters or longer", 400);
	}
	if ($requestData->password !== $requestData->passwordVerify) {
		returnError("Passwords do not match", 400);
	}
	if ($user = $db->users->where("email", $requestData->email)) {
		returnError("User already exists", 400);
	}
	
	$user = new user();
	$user->email = $requestData->email;
	$user->password = password_hash($requestData->password, PASSWORD_DEFAULT);
	$db->users->add($user);
	
	returnSuccess();