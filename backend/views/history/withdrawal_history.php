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
    $total_withdrawal_amount = 0;
    $transaction_type = "withdrawal";
    $payload = JWT::decode($token, new Key($SECRET_KEY, 'HS512'));

    //query
    $sql = "SELECT transaction_type, transaction_name, transaction_amount, amount_in_wallet, amount_in_wallet, created_at FROM transaction_details 
    WHERE transaction_type =:transaction_type AND user_id=:user_id ORDER BY transaction_id desc";
    $query = $con -> prepare($sql);
    $query->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
    $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
    if($query->execute()){
        $withdrawal_history = $query->fetchAll(PDO::FETCH_OBJ);
        foreach($withdrawal_history as $withdraw){
            $total_withdrawal_amount = $total_withdrawal_amount + $withdraw->transaction_amount;
        }
        $status = 200;
        $response = [
            "msg" => "Withdrawal history fetched successfully",
            "total_withdrawal_amount" => $total_withdrawal_amount,
            "withdrawal_history" => $withdrawal_history
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Can't fetch withdrawal history ."
        ];
    }
}
