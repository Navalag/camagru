<?php 

if (!isset($_SESSION)) {
	session_start();
}
include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

/*
** There are two cases here:
** - if user like photo
** - if user dislike photo
*/
if (isset($_POST['like']) && isset($_POST['img_id'])) {
	// test POST input for injection
	$img_id = test_input($_POST['img_id']);
	/*
	** check if img exists and if yes - select likes amount
	*/
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
	/*
	** check for malicious injection with same user
	** make more than one like for one img
	*/
	try {
		$sql = $conn->prepare("SELECT * FROM `likes` 
				WHERE `user_id` = $_SESSION[userID]
				AND `img_id` = $img_id LIMIT 1");
		$sql->execute();
	} catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}
	$check_user = $sql->setFetchMode(PDO::FETCH_ASSOC);
	$check_user = $sql->fetchAll();
	/*
	** stop script with error message if necessary
	*/
	if ((!empty($result)) && empty($check_user)) {
		$result = $result[0];
		$likes_amount = $result['likes'];
	} else {
		echo "error - malicious injection";
		exit();
	}
	/*
	** If all previous checks are success:
	** - insert row into `likes` table
	** - update `user_img` with increased `likes` value
	*/
	try {
		$sql = $conn->prepare("INSERT INTO `likes` (user_id, img_id)
				VALUES ($_SESSION[userID], $img_id)");
		$sql->execute();
		$sql = $conn->prepare("UPDATE `user_img` 
				SET `likes` = $likes_amount+1
				WHERE img_id = $img_id");
		$sql->execute();
	} catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}
	/*
	** send answer with increased likes amount to client side
	*/
	echo ++$likes_amount;
}
if (isset($_POST['unlike']) && isset($_POST['img_id'])) {
	// test POST input for injection
	$img_id = test_input($_POST['img_id']);
	/*
	** check if img exists and if yes - select likes amount
	*/
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
	/*
	** check for malicious injection with same user
	** make more than one like for one img
	*/
	try {
		$sql = $conn->prepare("SELECT * FROM `likes` 
				WHERE `user_id` = $_SESSION[userID]
				AND `img_id` = $img_id LIMIT 1");
		$sql->execute();
	} catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}
	$check_user = $sql->setFetchMode(PDO::FETCH_ASSOC);
	$check_user = $sql->fetchAll();
	/*
	** stop script with error message if necessary
	*/
	if ((!empty($result)) && (!empty($check_user))) {
		$result = $result[0];
		$likes_amount = $result['likes'];
	} else {
		echo "error - malicious injection";
		exit();
	}
	/*
	** If all previous checks are success:
	** - delete row into `likes` table
	** - update `user_img` with decreased `likes` value
	*/
	try {
		$sql = $conn->prepare("DELETE FROM `likes`
				WHERE `img_id`= $img_id 
				AND `user_id` = $_SESSION[userID]");
		$sql->execute();
		$sql = $conn->prepare("UPDATE `user_img` 
				SET `likes` = $likes_amount-1
				WHERE img_id = $img_id");
		$sql->execute();
	} catch (Exception $e) {
		echo "Unable to retrieved results";
		exit;
	}
	/*
	** send answer with increased likes amount to client side
	*/
	echo --$likes_amount;
}
/*
** turn off connection with database
*/
$conn = null;

?>
