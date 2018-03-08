<?php
	require_once("entity.php");

	class db {
		public $connection;
		public $APIKeys;
		public $tokens;
		public $uploads;
		public $users;
		
		function __construct() {
			require_once("config.php");
			$databaseServer = $config["server"];
			$database = $config["databaseName"];
			$databaseUser = $config["username"];
			$databasePassword = $config["password"];
			$this->connection = new mysqli($databaseServer, $databaseUser, $databasePassword, $database);
			$this->APIKeys = new entity("APIKeys", "APIKey", $this->connection);
			$this->uploads = new entity("uploads", "upload", $this->connection);
			$this->users = new entity("users", "user", $this->connection);
		}
	}
	