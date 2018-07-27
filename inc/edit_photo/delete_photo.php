<?php

if (!isset($_SESSION)) {
	session_start();
}
include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

/*
** DELETE PHOTO FROM SERVER FOLDER
*/
if (!empty($_GET["img_id"]) || !empty($_GET["pg"])) {
	$img_id = test_input($_GET["img_id"]);
	try {
		$result = $conn->prepare(
			"SELECT src FROM user_img 
			WHERE img_id = '$img_id'
			AND user_id = '$_SESSION[userID]' LIMIT 1");
		$result->execute();
		$file = $result->setFetchMode(PDO::FETCH_ASSOC);
		$file = $result->fetchAll();
		if (empty($file)) {
			echo "Unable to retrieved results";
			exit;
		}
		$file = str_replace('http://localhost:8080/', '', $file[0]['src']);
		$file = "../../" . $file;
		if (!unlink($file)) {
			echo ("Error deleting $file"."\n");
		}
		$sql = "DELETE FROM user_img WHERE img_id='$img_id'";
		$conn->exec($sql);
	} catch (PDOException $e) {
		echo "Unable to retrieved results";
		exit;
	}
	header("location:http://localhost:8080/account.php?pg=".$_GET['pg']);
}
$conn = null;

?>
