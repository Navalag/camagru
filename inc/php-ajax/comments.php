<?php

if (!isset($_SESSION)) {
	session_start();
}
include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

$name = $_SESSION['Username'];
$comment_entered = test_input($_POST['comment']);
$img_id = test_input($_POST['img_id']);
$date = date("d-m-Y");

/*
** check if user enter new comment than add it to database
*/
if ((!empty($comment_entered)) && (!empty($img_id))) {
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
	if ((!empty($result))) {
		$result = $result[0];
		$comment_amount = $result['comments'];
		$author_id = $result['user_id'];
	} else {
		echo "error - malicious injection";
		exit();
	}
	try {
		$sql = $conn->prepare("INSERT INTO `comments` (`img_id`, `name`, `date`, `comments`) 
			VALUES ('$img_id', '$name', '$date', '$comment_entered')");
		$sql->execute();
		$sql = $conn->prepare("UPDATE `user_img` 
				SET `comments` = $comment_amount+1
				WHERE img_id = $img_id");
		$sql->execute();
	}
	catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}

	/*
	** send notification to author of the img that it was commented
	*/
	try {
		$sql = $conn->prepare("SELECT * FROM `users` 
				WHERE `id` = $author_id LIMIT 1");
		$sql->execute();
	} catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}
	$result = $sql->setFetchMode(PDO::FETCH_ASSOC);
	$result = $sql->fetchAll();
	if (empty($result)) {
		echo "Unable to retrieved results";
		exit();
	} else {
		$result = $result[0];
		if ($result['notifications'] == 1) {
			sendmail_template_3($result['email'], $result['username']);
		}
	}
}

/*
** show all comments for specified photo
*/
if (!empty($img_id)) {
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
	if ((!empty($result))) {
		$result = $result[0];
		$comment_amount = $result['comments'];
	} else {
		echo "error - malicious injection";
		exit();
	}
	echo $comment_amount . " ";
	try {
		$sql = $conn->prepare("SELECT * FROM `comments` 
				WHERE `img_id` = $img_id
				ORDER BY `id` DESC");
		$sql->execute();
	}
	catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}
	$result = $sql->setFetchMode(PDO::FETCH_ASSOC);
	$result = $sql->fetchAll();
	if (!empty($result)) {
		foreach ($result as $item) {
			$name_field = $item['name'];
			$date_field = $item['date'];
			$comment_field = $item['comments'];

			echo "<div class='comment'>$name_field wrote: ($date_field)<br>";
			echo "$comment_field<br>";
			echo "</div>";
		}
	}
}
$conn = null;

?>
