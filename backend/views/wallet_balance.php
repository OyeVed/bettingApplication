<?php

require("dbcon.php");
require('middleware.php');
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){
    $available_amount = 0;
    //Total deposit on a particular day
    $secret_key = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    $payload = JWT::decode($token, new Key($secret_key, 'HS512'));

    //calculating funds available
    $query = $con->prepare("
        SELECT
        amount_in_wallet
        FROM transaction_details
        WHERE user_id=:user_id
        ORDER BY transaction_id DESC
        LIMIT 1
    ");
    $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
    if($query->execute()){
        $query->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $query->fetch()) {
            $available_amount = $row['amount_in_wallet'];
        }
        $status = 200;
        $response = [
            "msg" => $available_amount
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Wallet can't be fetched for now."
        ];
    }
}
