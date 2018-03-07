<?php
	class nonce {
		public $id;
		public $nonce;
		public $createdOn;
		public $lastAccessedOn;
		
		function __construct() {
			$this->createdOn = date("Y-m-d H:i:s");
			$this->lastAccessedOn = date("Y-m-d H:i:s");
		}
	}