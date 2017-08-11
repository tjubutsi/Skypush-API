<?php
	include "includes/helpers.php";
	include "includes/createUserFunctions.php";
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
	if($user = getUserByEmail($_POST["email"], $databaseConnection)) {
		returnResult("User already exists", 400);
	}
	
	createUser($_POST["email"], password_hash($_POST["password"], PASSWORD_DEFAULT), $databaseConnection);
	returnResult("User created");