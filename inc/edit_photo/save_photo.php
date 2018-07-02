<?php

if (!isset($_SESSION)) {
	session_start();
}
include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");

/*
** SAVE PHOTO TO SERVER FOLDER
*/
$img_folder = $_SERVER["DOCUMENT_ROOT"]."/img/uploads/";
$img_coded = $_POST['img'];
$img_coded = str_replace('data:image/png;base64,', '', $img_coded);
$img_coded = str_replace(' ', '+', $img_coded);
$img = base64_decode($img_coded);
$name = mktime() . ".png";
$file = $img_folder . $name;
file_put_contents($file, $img);
/*
** ADD PHOTO LINK TO DATABASE
*/
// $user = UserExist($_SESSION['loggued_on_user'], $pdo);
// $owner_id = $user['uid'];
$path = "http://localhost:8080/img/uploads/" . $name;
try {
	$sql = "INSERT INTO `user_img` (`src`, `user_id`) 
				VALUES ('$path', 1)";
	$conn->exec($sql);
	echo "New record created successfully<br>";
	}
catch(PDOException $e)
	{
	echo $sql . "<br>" . $e->getMessage();
	}
$conn = null;

// $pdo->query("INSERT INTO `images` (`owner_id`, `src`) VALUES
//     ('$owner_id', '$path')");
// echo $path;

?>