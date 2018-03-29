<?php
	if (isset($_SESSION["userId"])) {
		header("Location: /");
		die();
	}

	function login() {
		require_once __DIR__ . "/../includes/helpers.php";
	
		if (!$user = $db->users->where("email", $_POST["email"])) {
			return "User does not exist";
		}
		if ($user->isDisabled) {
			return "User is disabled";
		}
		if(!password_verify($_POST["password"], $user->password)){
			$user->loginTries = $user->loginTries + 1;
			if ($user->loginTries >= 3) {
				$user->isDisabled = 1;
			}
			$db->users->update($user);
			return "Wrong password";
		}
	    
		$user->loginTries = 0;
		$user->lastAccessedOn = date("Y-m-d H:i:s");
		$db->users->update($user);
		$_SESSION["userEmail"] = $user->email;
		$_SESSION["userId"] = $user->id;
		header("Location: /");
	}
	
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		$errorMessage = login();
	}
?>