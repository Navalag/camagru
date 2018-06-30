<?php 

include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions/user_managment_func.php");

$pageTitle = "Sign In - Camagru";
$section = null;

$nameErr = $passwordErr = $emailErr = "";
$finalMessage = "";
$username = $password = $email = "";

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
			$finalMessage = "Incorrect username or password.";
		} else {
			session_start();
			$_SESSION['Username'] = $check_user[0]["username"];
			header("location:http://localhost:8080/account.php");
		}
		}
	catch (PDOException $e)
		{
		$finalMessage = 'SignIn process failed. Reason: ' . $e->getMessage();
		}
	}
}
/*
** FORGOT PASSWORD SCRIPT
*/
if (isset($_POST['email'])) {
	if (empty($_POST["email"])) {
		$emailErr = "Can't find that email, sorry.";
	} else {
		$email = test_input($_POST['email']);
		// check if email exists in database
		$sql = $conn->prepare("SELECT * FROM `users` 
						WHERE `email` = '$email' LIMIT 1");
		$sql->execute();
		$check_email = $sql->setFetchMode(PDO::FETCH_ASSOC);
		$check_email = $sql->fetchAll();
		if (empty($check_email)) {
			$emailErr = "Can't find that email, sorry.";
		}
	}
	$new_password = random_str(10);
	$hash_new_password = md5($new_password);
	/*
	** SEND EMAIL
	*/
	if (empty($emailErr)) {
		if (sendmail_template_2($email, $new_password)) {
			try {
				$sql = "UPDATE `users` SET `password` = '$hash_new_password' 
						WHERE `email` = '$email' LIMIT 1";
				$conn->exec($sql);
				}
			catch (PDOException $e) 
				{
				$finalMessage = 'Database fail. Reason: ' . $e->getMessage();
				}
			$finalMessage = "Check your email for new password. If it doesn’t appear within a few minutes, check your spam folder.";
		} else {
			$finalMessage = "Error: Could not send email";
		}
	}
}

$conn = null;

include($_SERVER["DOCUMENT_ROOT"].'/inc/header.php');
?>

<!-- <!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Sign Up Form</title>
		<link rel="stylesheet" href="../css/normalize.css">
		<link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="../css/form.css">
	</head>
	<body> -->

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			
			<?php if (isset($_GET['forgot_pass']) || isset($_POST['email'])) { ?>

			<h1>Reset your password</h1>

			<p>Enter your email address and we will send you new password.</p>
			<label for="mail">Email:</label>
			<input type="email" id="mail" name="email">

			<p class="message" style="
			<?php 
				if (empty($emailErr) && empty($finalMessage)){ 
					echo "display: none;"; 
				}
			?>
			">
			<?php 
				if (!empty($emailErr)){ 
					echo $emailErr; 
				} else {
					echo $finalMessage; 
				}
			?>		
			</p>

			<button type="submit">Send new password</button>
			<a href="sign_in.php">Return to sign in</a>

			<?php } else { ?>

			<h1>Sign In</h1>
			
			<p class="message" style="
			<?php 
				if (empty($finalMessage)) {
					echo "display: none;"; 
				}
			?>
			"><?php echo $finalMessage;?></p>

			<label for="name">Username:</label>
			<input type="text" id="name" name="username">
			
			<label for="password">Password:</label>
			<input type="password" id="password" name="password">
								
			<button type="submit">Sign In</button>
			<a href="sign_in.php?forgot_pass=1">Forgot password?</a>
			<a href="/">Back to main page</a>

			<?php } ?>
			
		</form>
		
<?php include($_SERVER["DOCUMENT_ROOT"].'/inc/footer.php'); ?>
	<!-- </body>
</html> -->
