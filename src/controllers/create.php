<?php
	if (isset($_SESSION["userId"])) {
		header("Location: /");
		die();
	}

	function create() {
		require_once __DIR__ . "/../includes/helpers.php";
	
		if($_POST["email"] === "") {
			return "Email is required";
		}
		if(strlen($_POST["password"]) < 6) {
			return "Password too short, minimum length is 6 characters";
		}
		if($_POST["password"] !== $_POST["passwordVerify"]) {
			return "Passwords don't match";
		}
		if ($user = $db->users->where("email", $_POST["email"])) {
			return "User already exists";
		}
	    
		$user = new user();
		$user->email = $_POST["email"];
		$user->password = password_hash($_POST["password"], PASSWORD_DEFAULT);
		$db->users->add($user);
		$_SESSION["userEmail"] = $user->email;
		$_SESSION["userId"] = $user->id;
		header("Location: /");
	}
	
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$errorMessage = create();
	}
?>