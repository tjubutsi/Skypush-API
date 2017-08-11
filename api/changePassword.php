<?php
	include "includes/helpers.php";
	include "includes/changePasswordFunctions.php";
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnResult("Only POST method allowed", 405);
	}
	
	if (!isset($_SERVER["PHP_AUTH_USER"])) {
		returnResult("User missing", 400);
	}
	if (!isset($_SERVER["PHP_AUTH_PW"])) {
		returnResult("Original password missing", 400);
	}
	if(!$user = getUserByEmail($_SERVER["PHP_AUTH_USER"], $databaseConnection)){
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
	
	changePassword($user->id, password_hash($_POST["password"], PASSWORD_DEFAULT), $databaseConnection);
	returnResult("Password changed");