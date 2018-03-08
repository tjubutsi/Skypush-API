<?php
	class authorization {
		public $type;
		public $key;
		public $hash;
		
		function __construct($authorizationString) {
			if (!preg_match("/^([a-z]{4,4}) ([^:]{1,}):(.{1,})$/", $authorizationString, $authorizationArray)) {
				returnError("Invalid Authorization header");
			}
			$this->type = $authorizationArray[1][0];
			$this->key = $authorizationArray[2][0];
			$this->hash = $authorizationArray[3][0];
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