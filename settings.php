<?php 

include("config/connect.php");

if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['Username'])) {
	header("location:http://localhost:8080");
}

// $pageTitle = "Settings profile - Camagru";
// $section = null;

$finalMessage = "";
$username = $email = "";

try {
	$sql = $conn->prepare("SELECT * FROM `users` 
					WHERE `username` = '$_SESSION[Username]' LIMIT 1");
	$sql->execute();
	$user_info = $sql->setFetchMode(PDO::FETCH_ASSOC);
	$user_info = $sql->fetchAll();
	$user_info = $user_info[0];

	$username = $user_info['username'];
	$email = $user_info['email'];
	}
catch(PDOException $e)
	{
	$finalMessage = 'Database failed! Reason: ' . $e->getMessage();
	}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// try {
	// 	$sql = $conn->prepare("SELECT * FROM `users` 
	// 					WHERE `username` = '$_SESSION['Username']' LIMIT 1");
	// 	$sql->execute();
	// 	$user_info = $sql->setFetchMode(PDO::FETCH_ASSOC);
	// 	$user_info = $sql->fetchAll();
	// 	$user_info = $user_info[0];

	// 	$finalMessage = 'User has been confirmed. Thank-You!';
	// 	}
	// catch(PDOException $e)
	// 	{
	// 	$finalMessage = 'Database failed! Reason: ' . $e->getMessage();
	// 	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Sign Up Form</title>
		<link rel="stylesheet" href="css/normalize.css">
		<link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/form.css">
	</head>
	<body>

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
		
		<h1>Public profile</h1>
		
		<fieldset>
			
			<legend><span class="number">1</span> Your basic info</legend>
			
			<label for="name">Username:</label>
			<input type="text" id="name" name="user_name" value="<?php echo $username; ?>">
			
			<label for="mail">Email:</label>
			<input type="email" id="mail" name="user_email" value="<?php echo $email; ?>">
		
		</fieldset>

		<button type="submit">Update profile</button>
		
		</form>

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				
		<fieldset>
			
			<legend><span class="number">2</span> Change password</legend>
			
			<label for="old_password">Old Password:</label>
         <input type="password" id="old_password" name="old_password">

         <label for="new_password">New Password:</label>
         <input type="password" id="new_password" name="new_password">

         <label for="confirm_new_password">Confirm New Password:</label>
         <input type="password" id="confirm_new_password" name="confirm_new_password">
			
		</fieldset>
			
		<button type="submit">Update password</button>
		<a href="sign_in.php?forgot_pass=1">I forgot my password</a>
		<br><br><a href="/">Back to main page</a>
		
		</form>

	</body>
</html>
