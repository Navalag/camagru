<?php

if (!isset($_SESSION)) {
	session_start();
}
include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");

/*
** DELETE PHOTO FROM SERVER FOLDER
*/
if (!empty($_GET["img_id"]) || !empty($_GET["pg"])) {
	try {
		$result = $conn->prepare(
			"SELECT src FROM user_img WHERE img_id=" .
			$_GET['img_id']);
		$result->execute();
		$file = $result->setFetchMode(PDO::FETCH_ASSOC);
		$file = $result->fetchAll();
		$file = str_replace('http://localhost:8080/', '', $file[0]['src']);
		$file = "../../" . $file;
		if (!unlink($file)) {
			echo ("Error deleting $file"."\n");
		}
		$sql = "DELETE FROM user_img WHERE img_id=" . 
				$_GET['img_id'];

		$conn->exec($sql);
	} catch (PDOException $e) {
		echo "Unable to retrieved results";
		exit;
	}
	header("location:http://localhost:8080/account.php?pg=".$_GET['pg']);
}
$conn = null;

?>
