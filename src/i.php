<?php
	require_once __DIR__ . "/includes/helpers.php";
	session_start();
	$upload = $db->uploads->get($_GET["file"]);
	if (!$upload) {
		echo "Image does not exist";
		die();
	}
	if ($upload->isPrivate) {
		if (!isset($_SESSION['userId']) || $upload->user !== $_SESSION['userId']) {
			header('Location: /login.php');
		}
	}
	header("Content-Type: image/png");
	readfile("uploads/" . $upload->file);
