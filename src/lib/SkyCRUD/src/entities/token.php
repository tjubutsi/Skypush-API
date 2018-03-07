<?php
	class user {
		public $id;
		public $token;
		public $createdOn;
		public $lastAccessedOn;
		
		function __construct() {
			$this->createdOn = date("Y-m-d H:i:s");
			$this->lastAccessedOn = date("Y-m-d H:i:s");
		}
	}