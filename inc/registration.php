<?php 

include("config/connect.php");
//check if the form has been submitted
if(isset($_POST['signup'])){
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$email = mysql_real_escape_string($_POST['email']);

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

	if($action['result'] != 'error'){
		$password = md5($password);
		$sql = mysql_query("INSERT INTO `users` VALUES(NULL,'$username','$password','$email',0)");

		if($sql){
		 
			//the user was added to the database    
					 
		}else{
				 
			$action['result'] = 'error';
			array_push($text,'User could not be added to the database. Reason: ' . mysql_error());
			=
		}
	}

	$action['text'] = $text;

}
$conn = null;

?>