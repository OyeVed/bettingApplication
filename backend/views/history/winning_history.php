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
    $transaction_type = "won";
    $payload = JWT::decode($token, new Key($SECRET_KEY, 'HS512'));

    //query
    $sql = "SELECT transaction_type, transaction_name, transaction_amount, created_at FROM transaction_details 
    WHERE transaction_type =:transaction_type AND user_id=:user_id ORDER BY transaction_id desc";
    $query = $con -> prepare($sql);
    $query->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
    $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
    if($query->execute()){
        $win_history = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => $win_history
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Can't fetch win history."
        ];
    }
}
