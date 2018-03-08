<?php
	class APIKey {
		public $id;
		public $key;
		public $secret;
		public $user;
		public $isDisabled;
		public $createdOn;
		public $lastAccessedOn;
		
		function __construct() {
			$this->isDisabled = 0;
			$this->createdOn = date("Y-m-d H:i:s");
			$this->lastAccessedOn = date("Y-m-d H:i:s");
		}
	}