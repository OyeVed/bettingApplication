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
    
    try{
        $wallet = (object) [];

        //calculating funds available
        $query = $con->prepare("
        SELECT
        amount_in_wallet
        FROM transaction_details
        WHERE user_id=:user_id
        ORDER BY transaction_id DESC
        LIMIT 1 ");
        $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
        if($query->execute()){
            $query->setFetchMode(PDO::FETCH_ASSOC);
            while($row = $query->fetch()) {
                $available_amount = $row['amount_in_wallet'];
            }
            $wallet->available_amount = $available_amount;
        }else{
            $status = 203;
            $response = [
                "msg" => "Wallet can't be fetched for now."
            ];
        }

        $sql = "SELECT transaction_type, transaction_name, transaction_amount, created_at FROM transaction_details 
        WHERE user_id=:user_id ORDER BY transaction_id desc ";
        $query = $con -> prepare($sql);
        $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
        if($query->execute()){
            $statement = $query->fetchAll(PDO::FETCH_OBJ);
            $wallet->statement = $statement;
        }else{
            $status = 203;
            $response = [
                "msg" => "Can't fetch users statement."
            ];
        }

        //success response
        $status = 200;
        $response = [
            "msg" => "Wallet data fetched successfully",
            "wallet" => $wallet
        ];
    }catch(Exception $e){
        $status = 203;
        $response = [
            "msg" => "Wallet data can't be fetched.",
            "error" => $e->getMessage()
        ];
    }

}
