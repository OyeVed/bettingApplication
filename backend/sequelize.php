<?php

$database_tables = array(
    "
    CREATE TABLE IF NOT EXISTS `user_table` (
      `user_id` INT(11) PRIMARY KEY AUTO_INCREMENT,
      `user_username` VARCHAR(255) NOT NULL,
      `user_password` VARCHAR(255) NOT NULL,
      `user_email` varchar(255) NOT NULL,
      `user_phone` varchar(255) NOT NULL,
      `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
      `created_by` INT(11) NOT NULL,
      `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
      `updated_by` INT(11) NOT NULL
    );
    ",
);

require_once("dbcon.php");

foreach ($database_tables as $database_table) {
    try{

        $con->exec($database_table);
        
    } catch(PDOException $e){

        echo "Error creating table: " . $database_table . "<br>" . $e->error;

    }
}