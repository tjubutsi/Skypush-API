<?php
	if ($_SERVER["REQUEST_METHOD"] !== "GET") {
		returnResult("Only GET method allowed", 405);
	}
	include "helpers.php";
	$apiKey = bin2hex(random_bytes(16));
	
	$mysqli = new mysqli($databaseServer, $databaseUser, $databasePassword, $database);
	
	if ($mysqli->connect_errno) {
		returnResult("Creating key failed", 500);
	}
	
	$sql = "INSERT INTO clients (apiKey) VALUES('$apiKey');";
	if ($mysqli->query($sql) === true) {
		$mysqli->close();
		returnResult($apiKey);
	}
	else {
		$mysqli->close();
		returnResult("Creating key failed", 500);
	}
	
	returnResult($newToken);