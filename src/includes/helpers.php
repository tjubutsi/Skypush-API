<?php
	require_once(dirname(__FILE__) . "/../lib/SkyCRUD/src/db.php");
	$db = new db();
	
	function returnResult($message, $code = 200) {
		$data = new stdClass();
		$data->message = $message;
		http_response_code($code);
		echo json_encode($data);
		die();
	}