<div class="loginWrapper">
	<div class="loginForm">
		<form method="post" action="login">
			<input class="emailInput" type="text" name="email" value="" placeholder="email">
			<input class="passwordInput" type="password" name="password" value="" placeholder="Password">
			<input class="loginFormSubmit" type="submit" name="login" value="Login">
		</form>
		<?php if (isset($errorMessage)) {  ?>
			<div class="loginFormError"><?php echo $errorMessage; ?></div>
		<?php } ?>
	</div>
</div>