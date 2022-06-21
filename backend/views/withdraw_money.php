<?php

// import db connection
require("dbcon.php");
require('middleware.php');

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["user_jwt"];

// checking is the user authorized 
if(auth($token)){
    $user_id = $_POST['user_id'];
    $withdrawal_amount = $_POST['withdrawal_amount'];
    $upi_id = $_POST['upi_id'];
    $sql = "INSERT INTO  pending_request (user_id, withdrawal_amount, upi_id) VALUES ( :user_id, :withdrawal_amount, :upi_id)";
    $query = $con -> prepare($sql);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $query->bindParam(':upi_id', $upi_id, PDO::PARAM_STR);
    $query->bindParam(':withdrawal_amount', $withdrawal_amount, PDO::PARAM_STR);
    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "Withdrawal request added successfully"
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Withdrawal request can't be added"
        ];
    }
}