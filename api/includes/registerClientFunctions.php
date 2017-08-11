<?php
	function registerClient($databaseConnection){
		$token = bin2hex(random_bytes(16));
		$query = $databaseConnection->prepare("INSERT INTO clients (token) VALUES (?)");
		$query->bind_param("s", $token);
		$query->execute();
		$query->close();
		return $token;
	}