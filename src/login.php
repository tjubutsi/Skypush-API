<?php
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		require_once($_SERVER["DOCUMENT_ROOT"] . "/includes/helpers.php");
	
		if (!$user = $db->users->where("email", $_POST['email'])) {
			returnPage("User does not exist", 400);
		}
		if ($user->isDisabled) {
			returnPage("User is disabled", 401);
		}
		if(!password_verify($_POST['password'], $user->password)){
			$user->loginTries = $user->loginTries + 1;
			if ($user->loginTries >= 3) {
				$user->isDisabled = 1;
			}
			$db->users->update($user);
			returnPage("Wrong password", 401);
		}
	
		$user->loginTries = 0;
		$user->lastAccessedOn = date("Y-m-d H:i:s");
		$db->users->update($user);
		session_start();
		$_SESSION['userEmail'] = $user->email;
		$_SESSION['userId'] = $user->id;
		header('Location: /');
	}
	else {
?>

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Login Form</title>
</head>
<body>
  <section class="container">
    <div class="login">
      <h1>Login</h1>
      <form method="post" action="login.php">
        <p><input type="text" name="email" value="" placeholder="email"></p>
        <p><input type="password" name="password" value="" placeholder="Password"></p>

        <p class="submit"><input type="submit" name="commit" value="Login"></p>
      </form>
    </div>
</body>
</html>

<?php 
	} 
?>