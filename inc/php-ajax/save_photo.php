<?php

if (!isset($_SESSION)) {
	session_start();
}
include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

/*
** SAVE PHOTO TO SERVER FOLDER
*/
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['img'])) {
	$img_folder = $_SERVER["DOCUMENT_ROOT"]."/img/uploads/";
	$img_coded = test_input($_POST['img']);
	$img_coded = str_replace('data:image/png;base64,', '', $img_coded);
	$img_coded = str_replace(' ', '+', $img_coded);
	$img = base64_decode($img_coded);
	$name = mktime() . ".png";
	$file = $img_folder . $name;

	file_put_contents($file, $img);
	/*
	** ADD PHOTO LINK TO DATABASE
	*/
	$path = "http://localhost:8080/img/uploads/" . $name;
	try {
		// $sql = $conn->prepare("SELECT * FROM `users` 
		// 			WHERE `username` = '$_SESSION[Username]' LIMIT 1");
		// $sql->execute();
		// $user_info = $sql->setFetchMode(PDO::FETCH_ASSOC);
		// $user_info = $sql->fetchAll();
		// if (empty($user_info)) {
		// 	echo "Database Error!";
		// 	exit();
		// }
		// $user_info = $user_info[0];
		$sql = "INSERT INTO `user_img` (`src`, `user_id`) 
					VALUES ('$path', '$_SESSION[userID]')";
		$conn->exec($sql);
	} catch(PDOException $e) {
		echo $sql . "<br>" . $e->getMessage();
	}
}
$conn = null;

?>