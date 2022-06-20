<?php

require("dbcon.php");
require('middleware.php');
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// // Looing for .env at the root directory
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__. '/..');
// $dotenv->load();

// //Retrive env variable
// $SECRET_KEY = $_ENV['SECRET_KEY'];

$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["user_jwt"];

// checking is the user authorized 
if(auth($token)){
    $secret_key = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    $payload = JWT::decode($token, new Key($secret_key, 'HS512'));
    $old_password = md5($_POST['old_password']);
    $new_password = md5($_POST['new_password']);
    
    $sql = "SELECT * FROM user_table WHERE user_table.user_id=:user_id";
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
    
        if($user->user_password === $old_password){
            $sql = "UPDATE user_table SET user_password=:user_password WHERE user_id=:user_id";
            $query = $con -> prepare($sql);
            $query->bindParam(':user_password', $new_password, PDO::PARAM_STR);
            $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
            if($query->execute()){
                $status = 200;
                $response = [
                    "msg" => "Password updated successfully",
                    "payload" => $payload->user_phonenumber
                ];
            }else{
                $status = 203;
                $response = [
                    "msg" => "Password not updated"
                ];
            }
           
        }else{
            $status = 203;
            $response = [
                "msg" => "Old password does not match "
            ];
        }
    
    }
}
