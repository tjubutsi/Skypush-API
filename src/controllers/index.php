<?php
	function getUploads() {
		require_once __DIR__ . "/../includes/helpers.php";
		return $db->uploads->whereList("user", $_SESSION["userId"]);
	}
?>