<?php
	class authorization {
		public $type;
		public $APIKey;
		public $HMAC;
		
		function __construct($authorizationString) {
			if (!preg_match("/^([a-z]{4}) ([^:]+):(.+)$/", $authorizationString, $authorizationArray)) {
				returnError("Invalid Authorization header");
			}
			$this->type = $authorizationArray[1];
			$this->APIKey = $authorizationArray[2];
			$this->HMAC = $authorizationArray[3];
		}
	}

	class errorMessage {
		public $errorMessage;
		
		function __construct($message) {
			$this->errorMessage = $message;
		}
	}

	class successMessage {
		public $success;
		
		function __construct($success) {
			$this->success = $success;
		}
	}