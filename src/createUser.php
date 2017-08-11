<?php
	require_once(dirname(__FILE__) . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnResult("Only POST method allowed", 405);
	}
	
	if (!isset($_POST["email"])) {
		returnResult("Email missing", 400);
	}
	if (!isset($_POST["password"])) {
		returnResult("Password missing", 400);
	}
	if (!isset($_POST["passwordVerify"])) {
		returnResult("Password verification missing", 400);
	}
	if ($_POST["password"] !== $_POST["passwordVerify"]) {
		returnResult("Passwords do not match", 400);
	}
	if($user = $db->users->where("email", $_POST["email"])) {
		returnResult("User already exists", 400);
	}
	
	$user = new user();
	$user->email = $_POST["email"];
	$user->password = password_hash($_POST["password"], PASSWORD_DEFAULT);
	$db->users->add($user);

	returnResult("User created");