<?php	
	function getSessionByToken($token, $databaseConnection) {
		$query = $databaseConnection->prepare("SELECT id, ipHash, isDisabled, user, client FROM sessions WHERE token = ?");
		$query->bind_param("s", $token);
		$query->execute();
		$query->store_result();
		if($query->num_rows === 1) {
			$query->bind_result($session->id, $session->ipHash, $session->isDisabled, $session->user, $session->client);
			$query->fetch();
		}
		else {
			return false;
		}
		$query->close();
		return $session;
	}
	
	function getAccessToken($session) {
		$accessToken = bin2hex(random_bytes(16));
		$query = $databaseConnection->prepare("UPDATE lastAccessedOn = CURRENT_TIMESTAMP WHERE id = ?");
		$query->bind_param("i", $session->user);
		$query->execute();
		$query = $databaseConnection->prepare("UPDATE clients SET lastAccessedOn = CURRENT_TIMESTAMP WHERE id = ?");
		$query->bind_param("i", $session->client);
		$query->execute();
		$query = $databaseConnection->prepare("UPDATE sessions SET lastAccessedOn = CURRENT_TIMESTAMP WHERE id = ?");
		$query->bind_param("i", $session->id);
		$query->execute();
		$query = $databaseConnection->prepare("INSERT INTO accessTokens (token, session, validUntill) VALUES (?, ?, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 HOUR))");
		$query->bind_param("ss", $accessToken, $session->id);
		$query->execute();
		$query->close();
		return $accessToken;
	}