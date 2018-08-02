<?php 

include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

$pageTitle = "Sign Up - Camagru";
$section = "sign_up";

$nameErr = $emailErr = $passwordErr = $repeatPasswordErr = "";
$finalError = $finalSuccess = "";
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
		$sql = $conn->prepare("SELECT * FROM `users` 
						WHERE `username` = '$username' LIMIT 1");
		$sql->execute();
		$check_user = $sql->setFetchMode(PDO::FETCH_ASSOC);
		$check_user = $sql->fetchAll();
		if (!empty($check_user)) {
			$nameErr = "User with this username already exists";
		}
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
		$password = password_hash($password, PASSWORD_DEFAULT);
		try {
			$sql = $conn->prepare("INSERT INTO `users` 
					VALUES(NULL,'$username','$password','$email',1,0)");
			$sql->execute();
			}
		catch (PDOException $e)
			{
			$finalError = 'User could not be added to the database. Reason: ' . $e->getMessage();
			}
	}
	if (empty($finalError) && empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($repeatPasswordErr)) {
		/*
		** get the new user id
		** lastInsertId - Get the ID generated in the last query
		*/
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
				$finalSuccess = "Thank You for registration. Please check your email for confirmation!";
			} else {
				$finalError = "Error: Could not send confirm email";
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
			$finalError = 'Confirm row was not added to the database. Reason: ' . $e->getMessage();
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
		$finalSuccess = 'Your account has been confirmed.<br>You can now <a href="/inc/sign_in.php" class="message-link">Sign In.</a>';
		}
	catch(PDOException $e)
		{
		$finalError = 'The user could not be confirmed Reason: ' . $e->getMessage();
		}
}
$conn = null;

include($_SERVER["DOCUMENT_ROOT"].'/inc/header.php');
?>

<form class="form-container wide-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
	
	<h1>Sign Up</h1>

	<p class="message" style="
		<?php 
			if (empty($finalError)){ 
				echo "display: none;"; 
			}
		?>">
		<?php echo $finalError;?>
	</p>
	<p class="message success" style="
		<?php 
			if (empty($finalSuccess)){ 
				echo "display: none;"; 
			}
		?>">
		<?php echo $finalSuccess;?>
	</p>

	<p class="message" style="
		<?php 
			if (empty($nameErr)){ 
				echo "display: none;"; 
			}
		?>
		"><?php echo $nameErr;?>
	</p>
	<label for="name">Name: <span class="required">*</span></label>
	<input type="text" id="name" name="username" value="<?php echo $username;?>">
	
	<p class="message" style="
		<?php 
			if (empty($emailErr)){ 
				echo "display: none;"; 
			}
		?>
		"><?php echo $emailErr;?>
	</p>
	<label for="mail">Email: <span class="required">*</span></label>
	<input type="email" id="mail" name="email" value="<?php echo $email;?>">
	
	<p class="message" style="
		<?php 
			if (empty($passwordErr)){ 
				echo "display: none;"; 
			}
		?>
		"><?php echo $passwordErr;?>
	</p>
	<label for="password">Password: <span class="required">*</span></label>
	<input type="password" id="password" name="password">

	<p class="message" style="
		<?php 
			if (empty($repeatPasswordErr)){ 
				echo "display: none;"; 
			}
		?>
		"><?php echo $repeatPasswordErr;?>
	</p>
	<label for="repeat-password">Repeat Password: <span class="required">*</span></label>
	<input type="password" id="repeat-password" name="repeat_password">

	<input class="button" type="submit" value="Sign Up">
	<span class="form-text">Already have an acount? </span><a href="/inc/sign_in.php">Sign In</a>

</form>

<?php include($_SERVER["DOCUMENT_ROOT"].'/inc/footer.php'); ?>
