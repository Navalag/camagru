<?php 

include("./../config/connect.php");
include("./functions/user_managment_func.php");

$nameErr = $emailErr = $passwordErr = $repeatPasswordErr = "";
$finalMessage = "";
$username = $email = $password = $repeat_password = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
	/*
	** BEGIN FORM VALIDATION
	*/
	if (empty($_POST["username"])) {
    	$nameErr = "Name is required";
  	} else {
		$username = test_input($_POST['username']);
		// check if name only contains letters and whitespace
		if (!preg_match("/^[a-zA-Z ]*$/",$username)) {
			$nameErr = "Only letters and white space allowed"; 
		}
		// check if name exists in database
		// $sql = $conn->prepare("SELECT * FROM `users` 
		// 				WHERE `username` = '$username' LIMIT 1");
		// $sql->execute();
		// $check_user = $sql->setFetchMode(PDO::FETCH_ASSOC);
		// $check_user = $sql->fetchAll();
		// if (!empty($check_user)) {
		// 	$nameErr = "User with this username already exists";
		// }
	}

	if (empty($_POST["email"])) {
    	$emailErr = "Email is required";
  	} else {
		$email = test_input($_POST['email']);
		// check if e-mail address is well-formed
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr = "Invalid email format"; 
		}
		// check if email exists in database
		// $sql = $conn->prepare("SELECT * FROM `users` 
		// 				WHERE `email` = '$email' LIMIT 1");
		// $sql->execute();
		// $check_email = $sql->setFetchMode(PDO::FETCH_ASSOC);
		// $check_email = $sql->fetchAll();
		// if (!empty($check_email)) {
		// 	$emailErr = "This email already exists in our database";
		// }
	}

	if (empty($_POST["password"])) {
    	$passwordErr = "Password is required";
  	} else {
		$password = test_input($_POST['password']);
		// if (!preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/",$password)) {
		// 	$passwordErr = "Password must be at least 8 characters and must contain at least one lower case letter, one upper case letter and one digit"; 
		// }
	}

	if (empty($_POST["repeat_password"])) {
    	$repeatPasswordErr = "Please repeat your password";
  	} else {
		$repeat_password = test_input($_POST['repeat_password']);
		if ($password != $repeat_password) {
			$repeatPasswordErr = "Passwords doesn't match";
		}
	}

	/*
	** ADD USER TO DATABASE
	*/
	if (empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($repeatPasswordErr)) {
		$password = md5($password);
		try {
			$sql = $conn->prepare("INSERT INTO `users` 
					VALUES(NULL,'$username','$password','$email',0)");
			$sql->execute();
			}
		catch (PDOException $e)
			{
			$finalMessage = 'User could not be added to the database. Reason: ' . $e->getMessage();
			}
	}
	if (empty($finalMessage) && empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($repeatPasswordErr)) {
		// get the new user id
		// lastInsertId - Get the ID generated in the last query
		$userid = $conn->lastInsertId();
		//create a random key
		$key = $username . $email . date('mY');
		$key = md5($key);
		//add confirm row
		try {
			$sql = $conn->prepare("INSERT INTO `confirm` 
					VALUES(NULL,'$userid','$key','$email')");
			$sql->execute();
			/*
			** SEND EMAIL
			*/
			if (sendmail_template_1($email, $userid, $key)) {
				$finalMessage = "Thank You for registration. Please check your email for confirmation!";
			} else {
				$finalMessage = "Error: Could not send confirm email";
				$sql = $conn->prepare("DELETE FROM `users` 
							WHERE `username` = '$username' LIMIT 1");
				$sql->execute();
				$sql = $conn->prepare("DELETE FROM `confirm` 
							WHERE `userid` = '$userid' LIMIT 1");
				$sql->execute();
			}
			}
		catch(PDOException $e)
			{
			$finalMessage = 'Confirm row was not added to the database. Reason: ' . $e->getMessage();
			}
	}
}
if(isset($_GET['id']) && isset($_GET['code']))
{
	$id = test_input($_GET['id']);
	$code = test_input($_GET['code']);

	try {
		$sql = $conn->prepare("SELECT * FROM `confirm` 
						WHERE `userid` = '$id' AND `key` = '$code' LIMIT 1");
		$sql->execute();
		$check_key = $sql->setFetchMode(PDO::FETCH_ASSOC);
		$check_key = $sql->fetchAll();
		if (!empty($check_key)) {
			$check_key = $check_key[0];
			$sql = $conn->prepare("UPDATE `users` SET `active` = 1 
						WHERE `id` = '$check_key[userid]' LIMIT 1");
			$sql->execute();
			$sql = $conn->prepare("DELETE FROM `confirm` 
						WHERE `id` = '$check_key[id]' LIMIT 1");
			$sql->execute();
		}
		$finalMessage = 'User has been confirmed. Thank-You!';
		}
	catch(PDOException $e)
		{
		$finalMessage = 'The user could not be confirmed Reason: ' . $e->getMessage();
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
			
			<h1>Sign Up</h1>

			<p class="message" style="
				<?php 
					if (empty($finalMessage)){ 
						echo "display: none;"; 
					}
				?>">
				<?php echo $finalMessage;?>
			</p>

			<label for="name">Name:</label>
			<input type="text" id="name" name="username" value="<?php echo $username;?>">
			<p class="message" style="
			<?php 
				if (empty($nameErr)){ 
					echo "display: none;"; 
				}
			?>
			"><?php echo $nameErr;?></p>
			
			<label for="mail">Email:</label>
			<input type="email" id="mail" name="email" value="<?php echo $email;?>">
			<p class="message" style="
			<?php 
				if (empty($emailErr)){ 
					echo "display: none;"; 
				}
			?>
			"><?php echo $emailErr;?></p>
			
			<label for="password">Password:</label>
			<input type="password" id="password" name="password">
			<p class="message" style="
			<?php 
				if (empty($passwordErr)){ 
					echo "display: none;"; 
				}
			?>
			"><?php echo $passwordErr;?></p>

			<label for="repeat-password">Repeat Password:</label>
			<input type="password" id="repeat-password" name="repeat_password">
			<p class="message" style="
			<?php 
				if (empty($repeatPasswordErr)){ 
					echo "display: none;"; 
				}
			?>
			"><?php echo $repeatPasswordErr;?></p>

			<button type="submit" name="signup">Sign Up</button>
			<a href="sign_in.php">Sign In</a>
			<a href="/">Back to main page</a>
		</form>
	</body>
</html>
