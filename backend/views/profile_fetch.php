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

    //extracting payload from jwt
    $payload = JWT::decode($token, new Key($SECRET_KEY, 'HS512'));

    $sql = "SELECT user_phonenumber, user_email, user_fullname, upi_id, profile_image FROM user_table WHERE user_id=:user_id";
    $query = $con -> prepare($sql);
    $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);

    if($query->execute()){

        $user = $query->fetchAll(PDO::FETCH_OBJ)[0];

        if($user){
            $status = 200;
            $response = [
                "msg" => "User data fetched successfully",
                "user" => $user
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "User data can't be fetched now"
            ];
        }

    }else{
        $status = 203;
        $response = [
            "msg" => "Internal server error"
        ];
    }
}