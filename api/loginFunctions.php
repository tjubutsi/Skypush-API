<?php	
	class user {
		public $id;
		public $createdOn;
		public $lastAccessedOn;
		public $email;
		public $password;
		public $isDisabled;
		public $isLocked;
		public $loginTries;
	}

	function getClientIdByToken($token, $databaseConnection) {
		$query = $databaseConnection->prepare("SELECT id FROM clients WHERE token = ?");
		$query->bind_param("s", $token);
		$query->execute();
		$query->store_result();
		if($query->num_rows === 1) {
			$query->bind_result($clientId);
			$query->fetch();
		}
		else {
			return false;
		}
		$query->close();
		return $clientId;
	}

	function getUserByEmail($email, $databaseConnection) {
		$user = new user;
		$query = $databaseConnection->prepare("SELECT id, password, isDisabled, isLocked FROM users WHERE email = ?");
		$query->bind_param("s", $email);
		$query->execute();
		$query->store_result();
		if($query->num_rows === 1) {
			$query->bind_result($user->id, $user->password, $user->isDisabled, $user->isLocked);
			$query->fetch();
		}
		else {
			return false;
		}
		$query->close();
		return $user;
	}

	function processLoginSuccess($user, $client, $ipHash, $databaseConnection){
		$sessionToken = bin2hex(random_bytes(16));
		$query = $databaseConnection->prepare("UPDATE users SET loginTries = 0, lastAccessedOn = CURRENT_TIMESTAMP WHERE id = ?");
		$query->bind_param("s", $user->id);
		$query->execute();
		$query = $databaseConnection->prepare("UPDATE clients SET lastAccessedOn = CURRENT_TIMESTAMP WHERE id = ?");
		$query->bind_param("s", $client);
		$query->execute();
		$query = $databaseConnection->prepare("INSERT INTO sessions (token, user, client, ipHash, lastAccessedOn) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
		$query->bind_param("ssss", $sessionToken, $user->id, $client, $ipHash);
		$query->execute();
		$query->close();
		return $sessionToken;
	}

	function processWrongPassword($user, $databaseConnection){
		$query = $databaseConnection->prepare("UPDATE users SET loginTries = loginTries + 1, isLocked = CASE WHEN loginTries >= 3 THEN TRUE ELSE FALSE END WHERE id = ?");
		$query->bind_param("s", $user->id);
		$query->execute();
		$query->close();
	}