<?php
	$databaseServer = "127.0.0.1";
	$database = "skypushdev";
	$databaseUser = "skypush";
	$databasePassword = "skypush";
	$databaseConnection = new mysqli($databaseServer, $databaseUser, $databasePassword, $database);
	
	function returnResult($message, $code = 200) {
		$data = new stdClass();
		$data->message = $message;
		http_response_code($code);
		echo json_encode($data);
		die();
	}