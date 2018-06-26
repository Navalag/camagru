<?php 

include("./../../config/connect.php");
include("./../functions/user_managment_func.php");

$action = array();
$action['result'] = null;
$text = array();

//check if the form has been submitted
if(isset($_POST['signup'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];

	if(empty($username)) { 
		$action['result'] = 'error';
		array_push($text,'You forgot your username');
	}
	if(empty($password)) { 
		$action['result'] = 'error';
		array_push($text,'You forgot your password');
	}
	if(empty($email)) { 
		$action['result'] = 'error';
		array_push($text,'You forgot your email');
	}

	if($action['result'] != 'error') {
		$password = md5($password);
		try {
			$sql = $conn->prepare("INSERT INTO `users` 
					VALUES(NULL,'$username','$password','$email',0)");
			$sql->execute();
			echo "the user was added to the database<br>";
			}
		catch(PDOException $e)
			{
			$action['result'] = 'error';
			array_push($text,'User could not be added to the database. Reason: ' . $e->getMessage());
			}
	}
	if ($action['result'] != 'error') {
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
			echo "confirm table was updated<br>";
			// let's send the email
			if (email_template_1($email, $userid, $key)) {
				$action['result'] = 'success';
				$action['text'] = "Thanks for signing up. Please check your email for confirmation!";
			} else {
				$action['result'] = 'error';
				array_push($text,"Error: Could not send confirm email");
			}
			}
		catch(PDOException $e)
			{
			$action['result'] = 'error';
			array_push($text,'Confirm row was not added to the database. Reason: ' . $e->getMessage());
			}
	}

	// $action['text'] = $text;
	// $action = show_errors($action);
	// echo $action;
}
if(isset($_GET['id']) && isset($_GET['code']))
{
	$id = $_GET['id'];
	$code = $_GET['code'];

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
		$action['result'] = 'success';
		$action['text'] = 'User has been confirmed. Thank-You!';
		}
	catch(PDOException $e)
		{
		$action['result'] = 'error';
		$action['text'] = 'The user could not be updated Reason: ' . $e->getMessage();
		}
	// $action['text'] = $text;
	// $action = show_errors($action);
	// echo $action;
}
$conn = null;

?>
