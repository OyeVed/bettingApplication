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
        `profile_image` BLOB,
        `referral_code` VARCHAR(255) UNIQUE,
        `referral_by` VARCHAR(255) ,
        `created_at` DATETIME NOT NULL,
        `updated_at` DATETIME NOT NULL
    )",
    "
    CREATE TABLE IF NOT EXISTS `market_table` (
        `market_id` INT(255) PRIMARY KEY AUTO_INCREMENT,
        `market_fullname` VARCHAR(255) NOT NULL,
        `market_opentime` varchar(255) NOT NULL,
        `market_closetime` VARCHAR(255) NOT NULL,
        `market_on_days` VARCHAR(255) NOT NULL,
        `created_at` DATETIME NOT NULL,
        `updated_at` DATETIME NOT NULL
    )",
    "
    CREATE TABLE IF NOT EXISTS `game_rate_table` (
        `rate_id` INT(255) PRIMARY KEY AUTO_INCREMENT,
        `game_name` VARCHAR(255) NOT NULL,
        `game_rate` VARCHAR(255) NOT NULL DEFAULT 1,
        `reward_factor` VARCHAR(255) NOT NULL, 
        `created_at` DATETIME NOT NULL,
        `updated_at` DATETIME NOT NULL
    )",
    "
    CREATE TABLE IF NOT EXISTS `betting_history` (
        `bet_id` INT(255) PRIMARY KEY AUTO_INCREMENT,
        `market_id` INT(255),
        `user_id` INT(255),
        `game_name` VARCHAR(255) NOT NULL,
        `number` INT(255) NOT NULL,
        `points` INT(255) NOT NULL,
        `game_type` VARCHAR(255), 
        `created_at` DATETIME NOT NULL,
        `updated_at` DATETIME NOT NULL,
        FOREIGN KEY (market_id) 
    		REFERENCES market_table(market_id) 
    		ON DELETE CASCADE 
            ON UPDATE CASCADE,
        FOREIGN KEY (user_id) 
    		REFERENCES user_table(user_id) 
    		ON DELETE CASCADE 
            ON UPDATE CASCADE
    )",
    "
    CREATE TABLE IF NOT EXISTS `transaction_details` (
        `transaction_id` INT(255) PRIMARY KEY AUTO_INCREMENT,
        `user_id` INT(255),
        `transaction_type` VARCHAR(255) NOT NULL,
        `transaction_name` VARCHAR(255) NOT NULL,
        `transaction_amount` INT(255) NOT NULL,
        `amount_in_wallet` INT(255) NOT NULL,
        `created_at` DATETIME NOT NULL,
        `updated_at` DATETIME NOT NULL,
        FOREIGN KEY (user_id) 
            REFERENCES user_table(user_id)
            ON DELETE CASCADE 
            ON UPDATE CASCADE
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