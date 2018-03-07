<?php
	require_once("entity.php");

	class db {
		public $connection;
		
		public $clients;
		public $nonces;
		public $sessions;
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
			$this->clients = new entity("clients", "client", $this->connection);
			$this->nonces = new entity("nonces", "nonce", $this->connection);
			$this->sessions = new entity("sessions", "session", $this->connection);
			$this->tokens = new entity("tokens", "token", $this->connection);
			$this->uploads = new entity("uploads", "upload", $this->connection);
			$this->users = new entity("users", "user", $this->connection);
		}
	}
	