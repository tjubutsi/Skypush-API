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
	
	function verifyHMAC($HMAC, $message, $user, $timestamp, $nonce, $secret) {
		$hmacPieces = explode(":", $_SERVER["HTTP_AUTHORIZATION"]);
		$hmac = $hmacPieces[1];
		$message = file_get_contents('php://input');
		$user = $hmacPieces[0];
		$timestamp = $_SERVER["HTTP_TIMESTAMP"];
		$nonce = $_SERVER["HTTP_NONCE"];
		
		return hash_equals($HMAC, createHMAC($message, $user, $timestamp, $nonce, $secret));
	}
	
	function createHMAC($message, $user, $timestamp, $nonce, $secret) {
		$url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"]. $_SERVER["REQUEST_URI"];
		$secureMessage = $_SERVER["REQUEST_METHOD"] . $url . $message . $user . $timestamp . $nonce;
		return hash_hmac("sha-512", $secureMessage, $secret);
	}