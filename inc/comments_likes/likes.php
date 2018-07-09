<?php 

if (!isset($_SESSION)) {
	session_start();
}
include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

if (isset($_POST['liked'])) {
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
}
$conn = null;

?>
