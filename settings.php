<?php 

if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['Username'])) {
	header("location:http://localhost:8080");
}

include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

$pageTitle = "Settings profile - Camagru";
$section = null;

$nameErr = $passwordErr = $emailErr = "";
$finalMessage = "";
$username = $email = "";

/*
** select user info from DB, fill with this info name, email and password
*/
try {
	$sql = $conn->prepare("SELECT * FROM `users` 
					WHERE `username` = '$_SESSION[Username]' LIMIT 1");
	$sql->execute();
	$user_info = $sql->setFetchMode(PDO::FETCH_ASSOC);
	$user_info = $sql->fetchAll();
	if (empty($user_info)) {
		$finalMessage = "Error!";
	} else {
		$user_info = $user_info[0];
		$username = $user_info['username'];
		$email = $user_info['email'];
		$password = $user_info['password'];
	}
}
catch(PDOException $e) {
	$finalMessage = 'Database failed! Reason: ' . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	/*
	** validate data before updating profile
	*/
	if (empty($_POST["username"])) {
		$nameErr = "Name is required";
	} else {
		$new_username = test_input($_POST['username']);
		if ($_POST['username'] != $username) {
			// check if name only contains letters and whitespace
			if (!preg_match("/^[a-zA-Z ]*$/",$new_username)) {
				$nameErr = "Only letters and white space allowed"; 
			}
			// check if name exists in database
			$sql = $conn->prepare("SELECT * FROM `users` 
							WHERE `username` = '$new_username' LIMIT 1");
			$sql->execute();
			$check_user = $sql->setFetchMode(PDO::FETCH_ASSOC);
			$check_user = $sql->fetchAll();
			if (!empty($check_user)) {
				$nameErr = "User with this username already exists";
			}
		}
	}

	if (empty($_POST["email"])) {
		$emailErr = "Email is required";
	} else {
		$new_email = test_input($_POST['email']);
		if ($_POST['email'] != $email) {
			// check if e-mail address is well-formed
			if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "Invalid email format"; 
			}
			// check if email exists in database
			$sql = $conn->prepare("SELECT * FROM `users` 
							WHERE `email` = '$new_email' LIMIT 1");
			$sql->execute();
			$check_email = $sql->setFetchMode(PDO::FETCH_ASSOC);
			$check_email = $sql->fetchAll();
			if (!empty($check_email)) {
				$emailErr = "This email already exists in our database";
			}
		}
	}

	if (!empty($_POST["old_password"])) {
		$old_password = test_input($_POST['old_password']);
		$old_password = md5($old_password);
		if ($old_password != $password) {
			$passwordErr = "Old password incorrect.";
		} else {
			$new_password = test_input($_POST['new_password']);
			// if (!preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/",$new_password)) {
			// 	$passwordErr = "Password must be at least 8 characters and must contain at least one lower case letter, one upper case letter and one digit"; 
			// }
			if (empty($passwordErr)) {
				$confirm_new_password = test_input($_POST['confirm_new_password']);
				if ($new_password != $confirm_new_password) {
					$passwordErr = "Passwords doesn't match";
				} else {
					$new_password = md5($new_password);
					$password = $new_password;
				}
			}
		}
	}

	/*
	** UPDATE DATABASE WITH NEW VALUES
	*/
	if (empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($finalMessage)) {
		try {
			$sql = $conn->prepare("UPDATE `users` 
				SET `username` = '$new_username', `email` = '$new_email', `password` = '$password' 
				WHERE `username` = '$username' LIMIT 1");
			$sql->execute();
			$_SESSION['Username'] = $new_username;
			$username = $new_username;
			$email = $new_email;
			$finalMessage = 'Your profile was updated!';
			}
		catch(PDOException $e)
			{
			$finalMessage = 'Database failed! Reason: ' . $e->getMessage();
			}
	}
}
$conn = null;

include($_SERVER["DOCUMENT_ROOT"].'/inc/header.php');
?>

<form class="form-container" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

	<h1>Public profile</h1>

	<p class="message" style="
		<?php 
			if (empty($finalMessage)){ 
				echo "display: none;"; 
			}
		?>">
		<?php echo $finalMessage;?>
	</p>
	
	<fieldset>
			
		<legend><span class="number">1</span> Your basic info</legend>
		
		<p class="message" style="
			<?php 
				if (empty($nameErr)){ 
					echo "display: none;"; 
				}
			?>">
			<?php echo $nameErr;?>
		</p>
		<label for="name">Username:</label>
		<input type="text" id="name" name="username" value="<?php echo $username; ?>">
		
		<p class="message" style="
			<?php 
				if (empty($emailErr)){ 
					echo "display: none;"; 
				}
			?>">
			<?php echo $emailErr;?>
		</p>
		<label for="mail">Email:</label>
		<input type="email" id="mail" name="email" value="<?php echo $email; ?>">

	</fieldset>
	<fieldset>
			
		<legend><span class="number">2</span> Change password</legend>

		<p class="message" style="
			<?php 
				if (empty($passwordErr)){ 
					echo "display: none;"; 
				}
			?>">
			<?php echo $passwordErr;?>
		</p>
			
		<label for="old_password">Old Password:</label>
		<input type="password" id="old_password" name="old_password">

		<label for="new_password">New Password:</label>
		<input type="password" id="new_password" name="new_password">

		<label for="confirm_new_password">Confirm New Password:</label>
		<input type="password" id="confirm_new_password" name="confirm_new_password">
			
	</fieldset>
		
	<!-- <button type="submit">Update profile</button> -->
	<input class="button" type="submit" value="Update profile">
	<a href="/inc/sign_in.php?forgot_pass=1">I forgot my password</a>

</form>

<?php include($_SERVER["DOCUMENT_ROOT"].'/inc/footer.php'); ?>
