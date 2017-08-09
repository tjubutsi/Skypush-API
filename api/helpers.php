<?php
	function returnResult($message, $code = 200) {
		$data = new stdClass();
		$data->message = $message;
		http_response_code($code);
		echo json_encode($data);
		die();
	}
	
	class logLevel {
		public static $notification = 0;
		public static $warning = 1;
		public static $error = 2;
		public static $critical = 0;
	}
	
	$databaseServer = "127.0.0.1";
	$database = "skypushdev";
	$databaseUser = "skypush";
	$databasePassword = "skypush";
	$databaseConnection = new mysqli($databaseServer, $databaseUser, $databasePassword, $database);
	
	function writeLogEntry($message, $level, $databaseConnection) {
		$query = $databaseConnection->prepare("INSERT INTO log (message, level, occurredAt) VALUES (?, ?, CURRENT_TIMESTAMP)");
		$query->bind_param("ss", $message, $level);
		$query->execute();
		$query->close();
	}