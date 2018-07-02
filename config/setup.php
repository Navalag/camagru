<?php

include($_SERVER["DOCUMENT_ROOT"].'/config/connect.php');

try {
	/*
	** CREATE DATABASE
	*/
	$sql = "CREATE DATABASE IF NOT EXISTS " . $DB_NAME . ";";
	$sql .= "USE " . $DB_NAME . ";";
	$conn->exec($sql);
	// echo "Database created successfully<br>";
	/* 
	** CREATE user_img TABLE
	*/
	$sql = "CREATE TABLE IF NOT EXISTS user_img (
			img_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
			src VARCHAR(255) NOT NULL,
			user_id INT(6) NOT NULL
			)";
	$conn->exec($sql);
	// echo "Table user_img created successfully<br>";
	/* 
	** CREATE users TABLE
	*/
	$sql = "CREATE TABLE IF NOT EXISTS `users` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`username` varchar(50) NOT NULL default '',
			`password` varchar(128) NOT NULL default '',
			`email` varchar(250) NOT NULL default '',
			`active` binary(1) NOT NULL default '0'
			)";
	$conn->exec($sql);
	// echo "Table users created successfully<br>";
	/* 
	** CREATE confirm TABLE
	*/
	$sql = "CREATE TABLE IF NOT EXISTS `confirm` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`userid` varchar(128) NOT NULL default '',
			`key` varchar(128) NOT NULL default '',
			`email` varchar(250) default NULL
			)";
	$conn->exec($sql);
	// echo "Table confirm created successfully<br>";
	}
catch (PDOException $e)
	{
	echo $sql . "<br>" . $e->getMessage();
	}

$conn = null;

?>
