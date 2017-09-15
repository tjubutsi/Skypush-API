<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnJson("Only POST method allowed", 405);
	}
	
	if (!isset($_POST["email"])) {
		returnJson("Email missing", 400);
	}
	if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		returnJson("Email is invalid", 400);
	}
	if (!isset($_POST["password"])) {
		returnJson("Password missing", 400);
	}
	if (!isset($_POST["passwordVerify"])) {
		returnJson("Password verification missing", 400);
	}
	if (strlen($_POST["password"]) < 8) {
		returnJson("Password needs to be 8 characters or longer", 400);
	}
	if ($_POST["password"] !== $_POST["passwordVerify"]) {
		returnJson("Passwords do not match", 400);
	}
	if($user = $db->users->where("email", $_POST["email"])) {
		returnJson("User already exists", 400);
	}
	
	$user = new user();
	$user->email = $_POST["email"];
	$user->password = password_hash($_POST["password"], PASSWORD_DEFAULT);
	$db->users->add($user);

	returnJson("User created");