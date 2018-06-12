<?php

include('connect.php');

try {
    /* 
    ** CREATE DATABASE
    */
    $sql = "CREATE DATABASE IF NOT EXISTS " . $DB_NAME . ";";
    $sql .= "USE " . $DB_NAME . ";";
    $conn->exec($sql);
    echo "Database created successfully<br>";
    /* 
    ** CREATE TABLE
    */
    $sql = "CREATE TABLE IF NOT EXISTS user_img (
    		img_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    		src VARCHAR(255) NOT NULL,
    		user_id INT(6) NOT NULL
    		)";
    $conn->exec($sql);
    echo "Table user_img created successfully<br>";
	}
catch (PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;

?>
