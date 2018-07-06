<?php

include($_SERVER["DOCUMENT_ROOT"]."/config/connect.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

$name_entered = $_POST['name'];
$comment_entered = $_POST['comment'];
$date= date("m-d-Y");

if ((!empty($name_entered)) && (!empty($comment_entered))) {
	try {
		$sql = $conn->prepare("INSERT INTO `comments` (`name`, `date`, `comments`) VALUES ('$name_entered', '$date', '$comment_entered')");
		$sql->execute();
	}
	catch (PDOException $e) {
		echo $sql . "<br>" . $e->getMessage();
	}
}

try {
	$sql = $conn->prepare("SELECT * FROM `comments` ORDER BY `id` DESC");
	$sql->execute();
	$result = $sql->setFetchMode(PDO::FETCH_ASSOC);
	$result = $sql->fetchAll();
	if (!empty($result)) {
		foreach ($result as $item) {
			$name_field= $item['name'];
			$date_field= $item['date'];
			$comment_field= $item['comments'];

			echo "$name_field wrote: ($date_field) <br>";
			echo "$comment_field";
			echo "<br><hr><br>";
		}
	}
}
catch(PDOException $e)
	{
	echo $sql . "<br>" . $e->getMessage();
}

?>
