<?php

// import db connection
require("dbcon.php");
require('middleware.php');
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){

    //extracting payload from jwt
    $secret_key = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    $payload = JWT::decode($token, new Key($secret_key, 'HS512'));

    $sql = "SELECT * FROM user_table WHERE user_id=:user_id";
    $query = $con -> prepare($sql);
    $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
    $query->execute();

    if($query->rowCount() === 0){

        $status = 203;
        $response = [
            "msg" => "Bad Request - User does not exist"
        ];

    }else{

        $user = $query->fetchAll(PDO::FETCH_OBJ)[0];

        if($user){
            $status = 200;
            $response = [
                "msg" => "User data fetched successfully",
                "user" => [
                    'full_name' => $user->user_fullname,
                    "phone_number" => $user->user_phonenumber,
                    "email" => $user->user_email
                ]
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "Unauthorized - Password does not match"
            ];
        }

    }
}