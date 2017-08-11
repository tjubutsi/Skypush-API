<?php
	require_once(dirname(__FILE__) . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnResult("Only POST method allowed", 405);
	}
	
	if (!isset($_SERVER["PHP_AUTH_USER"])) {
		returnResult("User missing", 400);
	}
	if (!isset($_SERVER["PHP_AUTH_PW"])) {
		returnResult("Original password missing", 400);
	}
	if(!$user = $db->users->where("email", $_SERVER["PHP_AUTH_USER"])) {
		returnResult("User does not exist", 400);
	}
	if (!isset($_POST["password"])) {
		returnResult("New password missing", 400);
	}
	if (!isset($_POST["passwordVerify"])) {
		returnResult("New password verification missing", 400);
	}
	if ($_POST["password"] !== $_POST["passwordVerify"]) {
		returnResult("Passwords do not match", 400);
	}
	
	$user->password = password_hash($_POST["password"], PASSWORD_DEFAULT);
	$db->users->update($user);
	
	returnResult("Password changed");