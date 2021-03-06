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
    $total_winning_amount = 0;
    $transaction_type = "won";
    $payload = JWT::decode($token, new Key($SECRET_KEY, 'HS512'));

    //query
    $sql = "SELECT td.transaction_type, wh.game_name, td.transaction_name, td.transaction_amount, td.amount_in_wallet, td.created_at FROM transaction_details as td
    LEFT JOIN win_history as wh
    ON td.user_id = wh.user_id AND td.created_at = wh.created_at
    WHERE td.transaction_type = :transaction_type AND td.user_id=:user_id 
    ORDER BY td.transaction_id desc ";
    $query = $con -> prepare($sql);
    $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
    $query->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
    if($query->execute()){
        $winning_history = $query->fetchAll(PDO::FETCH_OBJ);
        foreach($winning_history as $win){
            $total_winning_amount = $total_winning_amount + $win->transaction_amount;
        }
        $status = 200;
        $response = [
            "msg" => "Winning history fetched successfully",
            "total_winning_amount" => $total_winning_amount,
            "winning_history" => $winning_history
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Can't fetch user winning history."
        ];
    }
}
