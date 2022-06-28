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
    $available_amount = 0;
    $user_id = $_POST['user_id'];
    $datetime = date("Y-m-d H:i:s");


    $query = $con->prepare("SELECT amount_in_wallet FROM transaction_details
    WHERE user_id=1 ORDER BY transaction_id DESC LIMIT 1");
    $query->execute();
    $result = $query->setFetchMode(PDO::FETCH_ASSOC);
    // echo "the result is ".$query->fetchAll()."\n";
    foreach((new RecursiveArrayIterator($query->fetchAll())) as $k=>$v) {
        $available_amount = $v['amount_in_wallet'];
    }

    //updating the transaction_details table
    $transaction_type = "deposit";
    $transaction_name = "Credited to wallet";
    $transaction_amount = 1000;
    $available_amount += $transaction_amount;
    $sql = "INSERT INTO transaction_details ( user_id, transaction_type, transaction_name, transaction_amount, amount_in_wallet, created_at, updated_at) VALUES
    (:user_id, :transaction_type, :transaction_name, :transaction_amount, :amount_in_wallet, :created_at, :updated_at)";
    $query = $con->prepare($sql);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $query->bindParam(':transaction_amount', $transaction_amount, PDO::PARAM_STR);
    $query->bindParam(':amount_in_wallet', $available_amount, PDO::PARAM_STR);
    $query->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
    $query->bindParam(':transaction_name', $transaction_name, PDO::PARAM_STR);
    $query->bindparam(":created_at", $datetime, PDO::PARAM_STR);
    $query->bindparam(":updated_at", $datetime, PDO::PARAM_STR);
    if($query->execute()){
        $status = 200;
        $response = [
            "msg" => "Transaction updated successfully"
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Transaction failed"
        ];
    }
}