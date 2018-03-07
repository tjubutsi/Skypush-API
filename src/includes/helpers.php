<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/lib/SkyCRUD/src/db.php");
	$db = new db();
	
	function returnJson($message, $code = 200) {
		$data = new stdClass();
		$data->message = $message;
		http_response_code($code);
		echo json_encode($data);
		die();
	}
	
	function returnPage($message, $code = 200) {
		http_response_code($code);
		echo $message;
		die();
	}
	
	function createToken() {
		$token = bin2hex(random_bytes(32));
		while ($db->sessions->where("token", $token)) {
			$token = bin2hex(random_bytes(32));
		}
		return $token;
	}
	
	function verifyHMAC($HMAC, $URI, $method, $user, $message, $timestamp, $nonce, $secret) {
		return hash_equals($HMAC, hash_hmac("sha-512", $URI . $method . $user . $message . $timestamp . $nonce), $secret)
	}