<div class="createUserWrapper">
	<div class="createUserForm">
		<form method="post" action="create">
			<input class="emailInput" type="text" name="email" value="<?php if (isset($_POST["email"])) { echo $_POST["email"]; } ?>" placeholder="email">
			<input class="passwordInput" type="password" name="password" placeholder="Password">
			<input class="passwordVerifyInput" type="password" name="passwordVerify" placeholder="Verify Password">
			<input class="createUserFormSubmit" type="submit" name="create" value="Create">
		</form>
		<?php if (isset($errorMessage)) {  ?>
			<div class="createUserFormError"><?php echo $errorMessage; ?></div>
		<?php } ?>
	</div>
</div>