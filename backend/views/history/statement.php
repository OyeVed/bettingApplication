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
    $statement_obj = (object)[];

    try{
        //fetching upi_id from user_table
        $sql = "SELECT upi_id FROM user_table WHERE user_id=:user_id";
        $query = $con -> prepare($sql);
        $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
        if($query->execute()){
            $upi_id = $query->fetchAll(PDO::FETCH_OBJ);
            $statement_obj->upi_id = $upi_id;
        }else{
            $status = 203;
            $response = [
                "msg" => "Upi_id can't be fetched."
            ];
        }


        //fetching statement from transaction_details table
        $sql = "SELECT transaction_type, transaction_name, transaction_amount, amount_in_wallet, created_at FROM transaction_details 
        WHERE user_id=:user_id ORDER BY transaction_id desc";
        $query = $con -> prepare($sql);
        $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
        if($query->execute()){
            $statement = $query->fetchAll(PDO::FETCH_OBJ);
            $statement_obj->statement = $statement;
            $statement_obj->amount_in_wallet = $statement[0]->amount_in_wallet;
        }else{
            $status = 203;
            $response = [
                "msg" => "Can't fetch statement."
            ];
        }
        $status = 200;
        $response = [
            "msg" => "User statement data fetched successfully.",
            "statement" => $statement_obj
        ];
    }catch(Exception $e){
        $status = 203;
        $response = [
            "msg" => "User statement data can't be fetched.",
            "error" => $e->getMessage()
        ];
    }
}
