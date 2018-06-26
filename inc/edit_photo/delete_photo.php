<?php

	if (!isset($_SESSION)) {
		session_start();
	}
	include('../../config/connect.php');

	/*
	** DELETE PHOTO FROM SERVER FOLDER
	*/
	try {
		$result = $conn->prepare(
			"SELECT src FROM user_img WHERE img_id=" .
			$_POST['img_id']);
		$result->execute();
		$file = $result->setFetchMode(PDO::FETCH_ASSOC);
		$file = $result->fetchAll();
		$file = str_replace('http://localhost:8080/', '', $file[0]['src']);
		$file = "../../" . $file;
		if (!unlink($file))
			{
			echo ("Error deleting $file"."\n");
			}
		else
			{
			echo ("Deleted $file"."\n");
			}
		// sql to delete a record
	    $sql = "DELETE FROM user_img WHERE img_id=" . 
	    		$_POST['img_id'];

	    // use exec() because no results are returned
	    $conn->exec($sql);
	    echo "Record deleted successfully";
		}
	catch (PDOException $e) {
		echo "Unable to retrieved results";
		exit;
		}

	$conn = null;

?>
