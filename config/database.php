<?php

$DB_DSN = "localhost";
$DB_USER = "root";
$DB_PASSWORD = "123456";
// $DB_NAME = "camagru";

if (!$connect = mysqli_connect($DB_DSN, $DB_USER, $DB_PASSWORD))
{
	$connect = mysqli_connect($DB_DSN, $DB_USER, $DB_PASSWORD);
	$base_created = false;
	if ($connect) {
		$createbase = mysqli_query($connect, "CREATE DATABASE camagru");
		if ($createbase) {
			$base_created = true;
		}
	} else {
		echo "Fail to connect";
	}
	if ($base_created)
	{
		$res = mysqli_query($connect, "CREATE TABLE users
		(userID int UNSIGNED AUTO_INCREMENT NOT NULL,
		login varchar(100) NOT NULL,
		email varchar(50) NOT NULL, 
		password varchar(100) NOT NULL UNIQUE,
		PRIMARY KEY(userID)
		)");
		if (!$res) {
			echo "<br/>Table already exists";
		}
		$admin_pass = hash('sha256', $DB_PASSWORD);
		$query = "INSERT INTO users(login, email, passwod)
			VALUES ('admin','admin@admin.com','".$admin_pass."');";
		mysqli_query($connect, $query);
	} else {
		echo "Fail to connect";
	}
}
mysqli_close($connect);

?>
