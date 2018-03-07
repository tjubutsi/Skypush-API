<?php
	class session {
		public $id;
		public $token;
		public $user;
		public $clientHash;
		public $ipHash;
		public $isDisabled;
		public $createdOn;
		public $lastAccessedOn;
		
		function __construct() {
			$this->isDisabled = 0;
			$this->createdOn = date("Y-m-d H:i:s");
			$this->lastAccessedOn = date("Y-m-d H:i:s");
		}
	}