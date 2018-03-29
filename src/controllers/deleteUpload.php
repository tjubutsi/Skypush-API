<?php
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
		header("Location: /");
		die();
	}
	
	if (!isset($_POST["file"])) {
		http_response_code(400);
		echo "No file specified";
		die();
	}
	
	require_once __DIR__ . "/../includes/helpers.php";
	$upload = $db->uploads->where("file", $_POST["file"]);
	$db->uploads->delete($upload);
	unlink($_SERVER["DOCUMENT_ROOT"] . "/i/" . $_POST["file"] . ".png");
	unlink($_SERVER["DOCUMENT_ROOT"] . "/i/t/" . $_POST["file"] . ".png");
	die();