<?php
require("dbcon.php");
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

use Dotenv\Dotenv;

// Looing for .env at the root directory
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

//Retrive env variable
$SECRET_KEY = $_ENV['SECRET_KEY'];


function auth($token){
    try {

        $secret_key = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
        $decoded = JWT::decode($token, new Key($secret_key, 'HS512'));

        // Access is granted. 
        // http_response_code(200);

        // echo json_encode(array(
        //     "message" => "Access granted:"
        // ));

        return true;
        
    }catch (Exception $e){

        // error code if access is not granted
        http_response_code(203);

        echo json_encode(array(
            "msg" => "Could not create the token for this API. Please contact your administrator.",
            "is_logged_out" => true,
            "error" => $e->getMessage()
        ));

        exit();
    }

}

















    // if($token){
    //     // echo "token in middleware received".$token;
    //     $secret_key = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    //     $decoded = JWT::decode($token, new Key($secret_key, 'HS512'));

    //     http_response_code(200);
    //     echo json_encode(array(
    //         "message" => "Access granted:"
    //         // "error" => $e->getMessage()
    //     ));
    // }else{
    //     http_response_code(200);
    //     echo json_encode(array(
    //         "message" => "Could not create the token for this API. Please contact your administrator.",
    //         "error" => $e->getMessage()
    //     ));
    // }