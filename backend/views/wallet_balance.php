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
    //Total deposit on a particular day
    $payload = JWT::decode($token, new Key($SECRET_KEY, 'HS512'));
    $available_amount = 0;

    //calculating funds available
    $query = $con->prepare(" SELECT amount_in_wallet FROM transaction_details
    WHERE user_id=:user_id ORDER BY transaction_id DESC LIMIT 1 ");
    $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
    if($query->execute()){
        $query->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $query->fetch()) {
            $available_amount = $row['amount_in_wallet'];
        }
        if($available_amount){
            $status = 200;
            $response = [
                "msg" => "Wallet balance fetched successfully",
                "wallet_balance" => $available_amount
            ];
        }else{
            $status = 200;
            $response = [
                "msg" => "No transactions found for user",
                "wallet_balance" => $available_amount
            ];
        }
        
    }else{
        $status = 203;
        $response = [
            "msg" => "Wallet can't be fetched for now."
        ];
    }

}