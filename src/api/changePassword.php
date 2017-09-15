<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnJson("Only POST method allowed", 405);
	}
	
	if (!isset($_SERVER["PHP_AUTH_USER"])) {
		returnJson("User missing", 400);
	}
	if (!isset($_SERVER["PHP_AUTH_PW"])) {
		returnJson("Original password missing", 400);
	}
	if(!$user = $db->users->where("email", $_SERVER["PHP_AUTH_USER"])) {
		returnJson("User does not exist", 400);
	}
	if (!isset($_POST["password"])) {
		returnJson("New password missing", 400);
	}
	if (!isset($_POST["passwordVerify"])) {
		returnJson("New password verification missing", 400);
	}
	if ($_POST["password"] !== $_POST["passwordVerify"]) {
		returnJson("Passwords do not match", 400);
	}
	
	$user->password = password_hash($_POST["password"], PASSWORD_DEFAULT);
	$db->users->update($user);
	
	returnJson("Password changed");