<?php
	require 'config/database.php'
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>sign in / sign up</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Audiowide" rel="stylesheet">
</head>
<body>
	<section id="header">
		<div class="name_of_game">
			<h2>Camagru</h2>
		</div>
	</section>
	<section id="wellcome">
		<section id="registration">
			<div class="registration_form">
				<form class="sign" action="admin/login.php" method="post">
					<h2>Login here</h2>
					<label><b>Username</b></label>
					<input type="text" name="uname" placeholder="User name">
					<label><b>Password</b></label>
					<input type="text" name="pass" placeholder="Password">
					<button type="submit">
						<b>Login</b>
					</button>
				</form>
			</div>
			<div>
				<form class="sign" action="admin/signup.php" method="post">
					<h2>Sign up here</h2>
					<label>Username</label>
					<input type="text" name="uname" placeholder="User name">
					<label>Email Add:</label>
					<input type="text" name="Email" placeholder="Email">
					<label>Password:</label>
					<input type="text" name="Password" placeholder="Password">
					<button type="submit">
						<b>Sign up</b>
					</button>
				</form>
			</div>
		</section>
	</section>
</body>
</html>
