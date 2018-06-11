<?php

include './database.php';
$object = new Install_db($db_location, $db_name, $db_user, $db_password);
$object->creat_db();

class Install_db {
    private $host;
    private $db_Name;
    private $user;
    private $password;
    public function __construct($host, $db_Name, $user, $password) {
        $this->host = $host;
        $this->db_Name = $db_Name;
        $this->user = $user;
        $this->password = $password;
    }
    public function creat_db() {
        try {
            $dataBase = new PDO($this->host, $this->user, $this->password);
            $this->is_db_not_exists($dataBase);
            $this->is_db_exist($dataBase);
            $this->is_table_img_not_exists($dataBase);
            echo ("Database ready");
        } catch (PDOException $e) {
            printf("Connection to database wasn't established: %s", $e->getMessage());
        }
    }
    private function is_db_not_exists($dataBase) {
        $request = "CREATE DATABASE IF NOT EXISTS `" . $this->db_Name . "`";
          $dataBase->query($request);
    }
    private function is_db_exist($dataBase) {
      $queryStatement = "USE `" . $this->db_Name . "`";
      $dataBase->query($queryStatement);
 	}
    private function is_table_img_not_exists($dataBase) {
      $queryStatement = "CREATE TABLE IF NOT EXISTS `user_img` (
                          `img_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                          `src` varchar(255) NOT NULL,
                          `user_id` varchar(255) NOT NULL)";
      $dataBase->query($queryStatement);
    }
}

?>