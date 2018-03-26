<?php
	session_start();
	require_once __DIR__ . "/" . $controller;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Skypush - <?=$title?></title>
		<link rel="stylesheet" type="text/css" href="css/css.css">
	</head>
	<body>
		<div class="page">
			<div class="header">
				<a class="link logo" href="/">Home</a>
				<a class="link downloadButton" href="/skypush/latest">Download</a>
				<?php if (isset($_SESSION['userId'])) { ?>
					<a class="link logoutButton" href="logout">logout</a>
				<?php } else { ?>
					<a class="link loginButton" href="login">Login</a>
					<a class="link createUserButton" href="create">Create User</a>
				<?php } ?>
			</div>
			<div class="content"><?php require_once __DIR__ . "/" . $view; ?></div>
		</div>
	</body>
</html>
