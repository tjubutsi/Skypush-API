<?php
	function getUserByEmail($email, $databaseConnection) {
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

	function changePassword($user, $passwordHash, $databaseConnection) {
		$query = $databaseConnection->prepare("UPDATE users SET password = ? WHERE id = ?");
		$query->bind_param("si", $passwordHash, $user);
		$query->execute();
	}