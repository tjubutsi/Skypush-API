<?php
	require_once("entity.php");

	class db {
		public $connection;
		public $accessTokens;
		public $clients;
		public $sessions;
		public $uploads;
		public $users;
		
		function __construct() {
			require_once("config.php");
			$databaseServer = $config["server"];
			$database = $config["databaseName"];
			$databaseUser = $config["username"];
			$databasePassword = $config["password"];
			$this->connection = new mysqli($databaseServer, $databaseUser, $databasePassword, $database);
			$this->accessTokens = new entity("accessTokens", "accessToken", $this->connection);
			$this->clients = new entity("clients", "client", $this->connection);
			$this->sessions = new entity("sessions", "session", $this->connection);
			$this->uploads = new entity("uploads", "upload", $this->connection);
			$this->users = new entity("users", "user", $this->connection);
		}
	}
	