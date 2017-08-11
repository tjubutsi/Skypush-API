<?php
	include "includes/helpers.php";
	include "includes/registerClientFunctions.php";
	if ($_SERVER["REQUEST_METHOD"] !== "GET") {
		returnResult("Only GET method allowed", 405);
	}
	
	$token = registerClient($databaseConnection);
	
	returnResult($token);