<?php 

include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

$pageTitle = "Sign In - Camagru";
$section = "sign_in";

$errorMessage = $successMessage = "";
$username = $password = $email = "";

if(isset($_POST["username"]) || isset($_POST["password"])) {
	/*
	** BEGIN FORM VALIDATION
	*/
	if (empty($_POST["username"])) {
		$errorMessage = "Username and password is required";
	} else {
		$username = test_input($_POST['username']);
	}
	if (empty($_POST["password"])) {
		$errorMessage = "Username and password is required";
	} else {
		$password = test_input($_POST['password']);
	}

	/*
	** CHECK USER IN DATABASE
	*/
	if (empty($errorMessage)) {
		try {
			$sql = $conn->prepare("SELECT * FROM `users` 
					WHERE `username` = '$username' AND `active` = 1 
					LIMIT 1");
			$sql->execute();
			$check_user = $sql->setFetchMode(PDO::FETCH_ASSOC);
			$check_user = $sql->fetchAll();
			if (!empty($check_user) 
				&& password_verify($password, $check_user[0]['password'])) {
				session_start();
				$_SESSION['Username'] = $check_user[0]["username"];
				$_SESSION['userID'] = $check_user[0]["id"];
				header("location:http://localhost:8080/account.php");
			} else {
				$errorMessage = "Incorrect username or password";
			}
		}
		catch (PDOException $e) {
			$errorMessage = 'SignIn process failed. Reason: ' . $e->getMessage();
		}
	}
}
/*
** FORGOT PASSWORD SCRIPT
*/
if (isset($_POST['email']) || isset($_GET['forgot_pass'])) {
	if (!empty($_POST["email"])) {
		$email = test_input($_POST['email']);
		// check if email exists in database
		$sql = $conn->prepare("SELECT * FROM `users` 
						WHERE `email` = '$email' LIMIT 1");
		$sql->execute();
		$check_email = $sql->setFetchMode(PDO::FETCH_ASSOC);
		$check_email = $sql->fetchAll();
		if (empty($check_email)) {
			$errorMessage = "Can't find that email, sorry.";
		}
	}
	$new_password = random_str(10);
	$hash_new_password = password_hash($new_password, PASSWORD_DEFAULT);
	/*
	** SEND EMAIL
	*/
	if (empty($errorMessage) && !empty($email)) {
		if (sendmail_template_2($email, $new_password)) {
			try {
				$sql = "UPDATE `users` SET `password` = '$hash_new_password' 
						WHERE `email` = '$email' LIMIT 1";
				$conn->exec($sql);
				}
			catch (PDOException $e) 
				{
				$errorMessage = 'Database fail. Reason: ' . $e->getMessage();
				}
			$successMessage = "Check your email for new password.<br>If it doesn’t appear within a few minutes, check your spam folder.";
		} else {
			$errorMessage = "Error: Could not send email";
		}
	}
}

$conn = null;

include($_SERVER["DOCUMENT_ROOT"].'/inc/header.php');
?>

<div class="container clearfix"  style="
			<?php 
				if (empty($errorMessage) && empty($successMessage)){ 
					echo "display: none;"; 
				}
			?>">
	
	<div class="alerts form-sign-in">
		<p class="message" style="
			<?php 
				if (empty($errorMessage)){ 
					echo "display: none;"; 
				}
			?>">
			<?php echo $errorMessage;?>
		</p>
		<p class="message success" style="
			<?php 
				if (empty($successMessage)){ 
					echo "display: none;"; 
				}
			?>">
			<?php echo $successMessage;?>
		</p>
	</div>

</div>

<form class="form-container" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
	
	<?php if (isset($_GET['forgot_pass']) || isset($_POST['email'])) { ?>

		<h2>Reset your password</h2>
		<p>Enter your email address and we will send you new password.</p>

		<label for="mail">Email:</label>
		<input type="email" id="mail" name="email">

		<input class="button" type="submit" value="Send new password">

		<?php if (!isset($_SESSION['Username'])) { ?>
			<a href="sign_in.php">Return to sign in</a>
		<?php } ?>

	<?php } else { ?>

		<h1>Sign In</h1>

		<label for="name">Username:</label>
		<input type="text" id="name" name="username">
		
		<label for="password">Password:</label>
		<input type="password" id="password" name="password">

		<input class="button" type="submit" value="Sign In">
		
		<a href="/inc/sign_in.php?forgot_pass=1">Forgot password?</a>

	<?php } ?>

</form>

<?php if (!isset($_SESSION['Username'])) { ?>

	<form class="form-container form-appendex">
		<span class="form-text">New at Camagru? </span><a href="/inc/sign_up.php">Create an account.</a>
	</form>

<?php } ?>

<?php include($_SERVER["DOCUMENT_ROOT"].'/inc/footer.php'); ?>
