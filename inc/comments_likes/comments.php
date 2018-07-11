<?php

include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

$name_entered = test_input($_POST['name']);
$comment_entered = test_input($_POST['comment']);
$img_id = test_input($_POST['img_id']);
$date = date("m-d-Y");

/*
** check if user enter new comment than add it to database
*/
if ((!empty($name_entered)) && (!empty($comment_entered)) && (!empty($img_id))) {
	try {
		$sql = $conn->prepare("INSERT INTO `comments` (`img_id`, `name`, `date`, `comments`) VALUES ('$img_id', '$name_entered', '$date', '$comment_entered')");
		$sql->execute();
	}
	catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}
}

/*
** show all comments for specified photo
*/
if (!empty($img_id)) {
	try {
		$sql = $conn->prepare("SELECT * FROM `comments` 
				WHERE `img_id` = $img_id
				ORDER BY `id` DESC");
		$sql->execute();
		$result = $sql->setFetchMode(PDO::FETCH_ASSOC);
		$result = $sql->fetchAll();
		if (!empty($result)) {
			foreach ($result as $item) {
				$name_field = $item['name'];
				$date_field = $item['date'];
				$comment_field = $item['comments'];

				echo "$name_field wrote: ($date_field) <br>";
				echo "$comment_field";
				echo "<br><hr><br>";
			}
		}
	}
	catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}
}

?>
