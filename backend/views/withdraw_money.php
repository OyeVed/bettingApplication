<?php

require("dbcon.php");
require('middleware.php');
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

use Dotenv\Dotenv;

// Looing for .env at the root directory
$dotenv = Dotenv::createImmutable('./');
$dotenv->load();

//Retrive env variable
$SECRET_KEY = $_ENV['SECRET_KEY'];

// getting token from cookie
$token = $_COOKIE["user_jwt"];

// checking is the user authorized 
if(auth($token)){

    $payload = JWT::decode($token, new Key($SECRET_KEY, 'HS512'));
    $user_id = $payload->user_id;
    $withdrawal_amount = $_POST['withdrawal_amount'];
    $upi_id = $_POST['upi_id'];
    $available_amount = 0;

    $query = $con->prepare(" SELECT amount_in_wallet FROM transaction_details
    WHERE user_id=:user_id ORDER BY transaction_id DESC LIMIT 1 ");
    $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
    if($query->execute()){
        $query->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $query->fetch()) {
            $available_amount = $row['amount_in_wallet'];
        }
        if($available_amount){
            if($available_amount >= $withdrawal_amount){
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
            }else{
                $status = 200;
                $response = [
                    "msg" => "Your wallet balance is low for this transaction",
                    "wallet_balance" => $available_amount
                ];
            }
        }else{
            $status = 200;
            $response = [
                "msg" => "No transactions found for user"
            ];
        }
        
    }else{
        $status = 203;
        $response = [
            "msg" => "Withdrawal request can't be placed for now."
        ];
    }
    
}