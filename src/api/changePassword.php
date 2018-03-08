<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnJson("Only POST method allowed", 405);
	}
	
	$user = verifyAuthorization(true)
	$requestData = json_decode(file_get_contents("php://input"));
	
	if (!isset($requestData->newPassword)) {
		returnError("New password missing", 400);
	} elseIf (!isset($requestData->newPasswordVerify)) {
		returnError("New password verification missing", 400);
	} elseIf ($requestData->newPassword !== $requestData->newPasswordVerify) {
		returnError("Passwords do not match", 400);
	}
	
	$user->password = password_hash($requestData->newPassword, PASSWORD_DEFAULT);
	$db->users->update($user);
	
	returnSuccess();