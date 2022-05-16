<?php


$database_tables = array(
    "
    CREATE TABLE IF NOT EXISTS `user_table` (
        `user_id` INT(11) PRIMARY KEY AUTO_INCREMENT,
        `user_password` VARCHAR(255) NOT NULL,
        `user_email` varchar(255) NOT NULL,
        `user_phonenumber` VARCHAR(255) NOT NULL,
        `user_fullname` VARCHAR(255) NOT NULL,
        `withdrawal_method` VARCHAR(255),
        `upi_id` VARCHAR(255),
        `bank_name` VARCHAR(255),
        `account_number` VARCHAR(255),
        `ifsc_code` VARCHAR(255),
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `created_by` INT(11),
        `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `updated_by` INT(11)
    )"
    
);

require_once("dbcon.php");

foreach ($database_tables as $database_table) {
    try{

        $con->exec($database_table);
        
    } catch(PDOException $e){

        echo "Error creating table: " . $database_table . "<br>" . $e->error;
        // print_r($e);

    }
}