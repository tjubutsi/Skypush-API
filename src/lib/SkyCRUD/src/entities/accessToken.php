<?php
	class accessToken {
		public $id;
		public $token;
		public $session;
		public $validUntill;
		public $isDisabled;
		public $createdOn;
		public $lastAccessedOn;
		
		function __construct() {
			$this->validUntill = date("Y-m-d H:i:s", strtotime("+1 hours"));
			$this->isDisabled = 0;
			$this->createdOn = date("Y-m-d H:i:s");
		}
	}