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
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){

    // $secret_key = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    $payload = JWT::decode($token, new Key($SECRET_KEY, 'HS512'));
    
    // retrieve required variables
    $upi_id = $_POST['upi_id'];
    
    
    $sql = "UPDATE user_table SET 
    upi_id=:upi_id
    WHERE user_id=:user_id";
    $query = $con -> prepare($sql);
    $query->bindParam(':upi_id', $upi_id, PDO::PARAM_STR);
    $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
    if($query->execute()){
        $user = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => "Upi added successfully"
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Internal server error- Upi can't be added"
        ];
    }
}