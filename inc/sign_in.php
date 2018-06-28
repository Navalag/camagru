<?php 

include("./../config/connect.php");
include("./functions/user_managment_func.php");

$nameErr = $passwordErr = "";
$finalMessage = "";
$username = $password = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
	/*
	** BEGIN FORM VALIDATION
	*/
	if (empty($_POST["username"])) {
    	$nameErr = "Please enter your Username";
  	} else {
		$username = test_input($_POST['username']);
	}
	if (empty($_POST["password"])) {
    	$passwordErr = "Please enter your Password";
  	} else {
		$password = test_input($_POST['password']);
	}

	/*
	** CHECK USER IN DATABASE
	*/
	if (empty($nameErr) && empty($passwordErr)) {
	$password = md5($password);
	try {
		$sql = $conn->prepare("SELECT * FROM `users` 
				WHERE `username` = '$username' AND `password` = '$password' AND `active` = 1");
		$sql->execute();
		$check_user = $sql->setFetchMode(PDO::FETCH_ASSOC);
		$check_user = $sql->fetchAll();
		if (empty($check_user)) {
			$finalMessage = "Your Username or Password is invalid";
		} else {
			header("location:http://localhost:8080/account.php");
		}
		}
	catch (PDOException $e)
		{
		$finalMessage = 'SignIn process failed. Reason: ' . $e->getMessage();
		}
	}
}

$conn = null;

?>

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

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			
			<h1>Sign In</h1>
			
			<p class="message"><?php echo $finalMessage;?></p>

			<label for="name">Username:</label>
			<input type="text" id="name" name="username">
			
			<label for="password">Password:</label>
			<input type="password" id="password" name="password">
								
			<button type="submit">Sign In</button>
			
		</form>
		
	</body>
</html>
