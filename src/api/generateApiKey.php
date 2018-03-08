<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		returnJson("Only POST method allowed", 405);
	}
	
	$APIKey = new APIKey();
	
	if (isset($_SERVER["HTTP_AUTHORIZATION"])) {
		verifyAuthorization();
		$requestData = json_decode(file_get_contents("php://input"));
		$user = $db->users->where("email", $requestData->user)
		if ($user->isDisabled) {
			returnError("User is disabled", 403);
		}
		if(!password_verify($requestData->password, $user->password)){
			$user->loginTries += 1;
			if ($user->loginTries >= 3) {
				$user->isDisabled = 1;
			}
			$db->users->update($user);
			returnError("Invalid password", 403);
		}
		$user->loginTries = 0;
		$user->lastAccessedOn = date("Y-m-d H:i:s");
		$db->users->update($user);
		$APIKey->user = $user->id;
	}
	
	$APIKey->key = createToken();
	while ($db->APIKey->where("key", $APIKey->key)) {
		$APIKey->key = createToken();
	}
	$APIKey->secret = createToken();
	$APIKey->clientHash = password_hash($_SERVER["HTTP_USER_AGENT"], PASSWORD_DEFAULT);
	$APIKey->ipHash = password_hash($_SERVER["REMOTE_ADDR"], PASSWORD_DEFAULT);

	$db->APIKeys->add($APIKey);
	returnData($APIKey);