<?php 

include("config/connect.php");
include("inc/email_templates/email_template_1.php");
//check if the form has been submitted
if(isset($_POST['signup'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];

	$action = array();
	$action['result'] = null;
	$text = array();

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
			$sql->exec();
			echo "the user was added to the database";
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
			$sql->exec();
			// let's send the email
			if (email_template_1($email, $userid, $key)) {
				echo "An Activation Code Is Sent To You Check You Emails";
			} else {
				echo "Error: An Activation Code Did Not Send";
			}
			}
		catch(PDOException $e)
			{
			$action['result'] = 'error';
			array_push($text,'Confirm row was not added to the database. Reason: ' . $e->getMessage());
			}
	}

	$action['text'] = $text;

}
if(isset($_GET['id']) && isset($_GET['code']))
{
	$id=$_GET['id'];
	$code=$_GET['id'];
	mysql_connect('localhost','root','');
	mysql_select_db('sample');
	$select=mysql_query("select email,password from verify where id='$id' and code='$code'");
	if(mysql_num_rows($select)==1)
	{
		while($row=mysql_fetch_array($select))
		{
			$email=$row['email'];
			$password=$row['password'];
		}
		$insert_user=mysql_query("insert into verified_user values('','$email','$password')");
		$delete=mysql_query("delete from verify where id='$id' and code='$code'");
	}
}
$conn = null;

?>