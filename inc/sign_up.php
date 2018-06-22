<!DOCTYPE html>
<html>
	<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Sign Up Form</title>
			<link rel="stylesheet" href="../css/normalize.css">
			<link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
			<link rel="stylesheet" href="../css/form.css">
	</head>
	<body>
		<form action="./registration.php" method="post">
			
			<h1>Sign Up</h1>
				
			<label for="name">Nick-name:</label>
			<input type="text" id="name" name="user_name">
			
			<label for="mail">Email:</label>
			<input type="email" id="mail" name="user_email">
			
			<label for="password">Password:</label>
			<input type="password" id="password" name="user_password">

			<label for="repeat-password">Repeat Password:</label>
			<input type="password" id="repeat-password" name="user_repeat_password">

			<button type="submit">Sign Up</button>

		</form>
	</body>
</html>
