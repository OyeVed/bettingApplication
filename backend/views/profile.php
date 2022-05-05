<?php

//Require database

require("dbcon.php");

if(isset($_POST["submit"])){
    if($_POST["username"] === $_SESSION["username"]){
        $first_name = $_POST["fname"];
        $last_name = $_POST["lname"];
        $email = $_POST["email"];
        $account_no = $_POST["account_no"];
        $sql = "INSERT INTO user_table (first_name, last_name, email, account_no, ) VALUES ('$first_name', '$last_name', '$email', '$account_no')";
        $query = $con -> prepare($sql);
        $query->execute();
    }
}
