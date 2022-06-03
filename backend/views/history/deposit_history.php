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
    //Total deposit on a particular day
    $transaction_type = "deposit";
    $secret_key = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    $payload = JWT::decode($token, new Key($secret_key, 'HS512'));

    //query
    $sql = "SELECT transaction_type, transaction_name, transaction_amount, amount_in_wallet FROM transaction_details 
    WHERE transaction_type =:transaction_type AND user_id=:user_id";
    $query = $con -> prepare($sql);
    $query->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
    $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
    if($query->execute()){
        $deposit_history = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => $deposit_history
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Can't fetch deposit history."
        ];
    }
}
