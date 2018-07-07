<?php 

if (!isset($_SESSION)) {
	session_start();
}
include($_SERVER["DOCUMENT_ROOT"]."/config/setup.php");
include($_SERVER["DOCUMENT_ROOT"]."/inc/functions.php");

if (isset($_POST['liked'])) {
	$postid = $_POST['postid'];
	$result = mysqli_query($con, "SELECT * FROM posts WHERE id=$postid");
	$row = mysqli_fetch_array($result);
	$n = $row['likes'];

	mysqli_query($con, "INSERT INTO likes (userid, postid) VALUES (1, $postid)");
	mysqli_query($con, "UPDATE posts SET likes=$n+1 WHERE id=$postid");

	echo $n+1;
	exit();
}
if (isset($_POST['unliked'])) {
	$postid = $_POST['postid'];
	$result = mysqli_query($con, "SELECT * FROM posts WHERE id=$postid");
	$row = mysqli_fetch_array($result);
	$n = $row['likes'];

	mysqli_query($con, "DELETE FROM likes WHERE postid=$postid AND userid=1");
	mysqli_query($con, "UPDATE posts SET likes=$n-1 WHERE id=$postid");
	
	echo $n-1;
	exit();
}

// Retrieve posts from the database
$posts = mysqli_query($con, "SELECT * FROM posts");

?>