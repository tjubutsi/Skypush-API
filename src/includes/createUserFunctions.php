<?php
	function getUserByEmail($email, $databaseConnection) {
		$user = new user;
		$query = $databaseConnection->prepare("SELECT id, password, isDisabled FROM users WHERE email = ?");
		$query->bind_param("s", $email);
		$query->execute();
		$query->store_result();
		if($query->num_rows === 1) {
			$query->bind_result($user->id, $user->password, $user->isDisabled);
			$query->fetch();
		}
		else {
			return false;
		}
		$query->close();
		return $user;
	}

	function createUser($email, $passwordHash, $databaseConnection ){
		$query = $databaseConnection->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
		$query->bind_param("ss", $email, $passwordHash);
		$query->execute();
	}