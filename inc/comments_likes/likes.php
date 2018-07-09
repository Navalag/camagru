<?php 

if (!isset($_SESSION)) {
	session_start();
}
include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

if (isset($_POST['liked'])) {
	$img_id = $_POST['img_id'];
	// $result = mysqli_query($con, "SELECT * FROM `user_img` 
	// 	WHERE `img_id`=$img_id");
	// $row = mysqli_fetch_array($result);
	try {
		$sql = $conn->prepare("SELECT * FROM `user_img` 
				WHERE `img_id` = $img_id LIMIT 1");
		$sql->execute();
	} catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}
	$result = $sql->setFetchMode(PDO::FETCH_ASSOC);
	$result = $sql->fetchAll();
	if (!empty($result)) {
		$result = $result[0];
		$n = $result['likes'];
	} else {
		echo "error";
		exit();
	}
	try {
		$sql = $conn->prepare("INSERT INTO `likes` (userid, postid)
				VALUES ($_SESSION[userID], $img_id)");
		$sql->execute();
		$sql = $conn->prepare("UPDATE `user_img` 
				SET `likes` = $n+1
				WHERE img_id=$img_id");
		$sql->execute();
	} catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}

	// mysqli_query($con, "INSERT INTO likes (userid, postid) VALUES (1, $img_id)");
	// mysqli_query($con, "UPDATE posts SET likes=$n+1 WHERE id=$img_id");

	echo $n+1;
}
if (isset($_POST['unliked'])) {
	$img_id = $_POST['img_id'];
	try {
		$sql = $conn->prepare("SELECT * FROM `user_img` 
				WHERE `img_id` = $img_id LIMIT 1");
		$sql->execute();
	} catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}
	$result = $sql->setFetchMode(PDO::FETCH_ASSOC);
	$result = $sql->fetchAll();
	if (!empty($result)) {
		$result = $result[0];
		$n = $result['likes'];
	} else {
		echo "error";
		exit();
	}
	try {
		$sql = $conn->prepare("DELETE FROM `likes`
				WHERE `img_id`= $img_id 
				AND `user_id`= $_SESSION[userID]");
		$sql->execute();
		$sql = $conn->prepare("UPDATE `user_img` 
				SET `likes` = $n-1
				WHERE img_id=$img_id");
		$sql->execute();
	} catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}

	echo $n-1;


	// $img_id = $_POST['img_id'];
	// $result = mysqli_query($con, "SELECT * FROM posts WHERE id=$img_id");
	// $row = mysqli_fetch_array($result);
	// $n = $row['likes'];

	// mysqli_query($con, "DELETE FROM likes WHERE postid=$img_id AND userid=1");
	// mysqli_query($con, "UPDATE posts SET likes=$n-1 WHERE id=$img_id");
	
	// echo $n-1;
	// exit();
}
$conn = null;
// Retrieve posts from the database
// $posts = mysqli_query($con, "SELECT * FROM posts");

?>
