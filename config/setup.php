<?php

include('database.php');

try {
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    /* 
    ** DATABASE STRUCTURE
    */
    // create database
    $sql = "CREATE DATABASE IF NOT EXISTS " . $DB_NAME . ";";
    $sql .= "USE " . $DB_NAME . ";";
    $conn->exec($sql);
    echo "Database created successfully<br>";
    // create table
    $sql = "CREATE TABLE IF NOT EXISTS user_img (
    		img_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    		src VARCHAR(255) NOT NULL,
    		user_id INT(6) NOT NULL
    		)";
    $conn->exec($sql);
    echo "Table user_img created successfully<br>";
	}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

// $conn = null;

?>
