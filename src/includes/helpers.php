<?php
	require_once(dirname(__FILE__) . "/../lib/SkyCRUD/src/db.php");
	require_once(dirname(__FILE__) . "/models.php");
	
	$db = new db();
	
	function returnData($object, $code = 200) {
		http_response_code($code);
		echo json_encode($object);
		die();
	}
	
	function returnError($message, $code = 400) {
		returnData(new errorMessage($message), $code);
	}
	
	function returnDataWithHMAC($object, $code = 200) {
		http_response_code($code);
		echo json_encode($object);
		die();
	}
	
	function returnErrorWithHMAC($message, $code = 400) {
		returnDataWithHMAC(new errorMessage($message), $code);
	}
	
	function returnSuccess($success = true, $code = 200) {
		returnData(new successMessage($success), $code);
	}
	
	function createToken() {
		return bin2hex(random_bytes(32));
	}
	
	function verifyAuthorization($userRequired = false) {
		global $db;
		$authorization = new authorization($_SERVER["HTTP_AUTHORIZATION"]);
		$message = file_get_contents('php://input');
		if (!$APIKey = $db->APIKeys->where("APIKey", $authorization->APIKey)) {
			returnError("API key does not exist");
		}
		if ($APIKey->isDisabled) {
			returnError("API key is disabled", 403);
		}
		if (!hash_equals($authorization->HMAC, createHMAC($message, $authorization->APIKey, $APIKey->secret))) {
			$APIKey->isDisabled = 1;
			$db->APIKeys->update($APIKey);
			returnError("HMAC signature validation failed, disabling API key", 403);
		}
		
		if ($APIKey->user) {
			if (!$user = $db->users->get($APIKey->user)) {
				returnError("User for this request doesn't exist? This shouldn't happen", 500); //todo add errorlog stuff
			}
			if ($user->isDisabled) {
				$APIKey->isDisabled = 1;
				$db->APIKeys->update($APIKey);
				returnError("User is disabled, disabling API key", 403);
			}
			$user->lastAccessedOn = date("Y-m-d H:i:s");
			$db->users->update($user);
		} elseif ($userRequired) {
			returnError("User API key required for this request", 403);
		}
		
		$APIKey->lastAccessedOn = date("Y-m-d H:i:s");
		$db->APIKeys->update($APIKey);
		
		if ($APIKey->user) {
			return $user;
		}
		
		return false;
	}
	
	function createHMAC($message, $key, $secret) {
		$url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"]. $_SERVER["REQUEST_URI"];
		$message = $_SERVER["REQUEST_METHOD"] . $url . $message . $key;
		return hash_hmac("sha512", $message, $secret);
	}