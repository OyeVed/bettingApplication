<?php

// import db connection
require("dbcon.php");
require_once('../vendor/autoload.php');
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

// Looing for .env at the root directory
$dotenv = Dotenv::createImmutable('./');
$dotenv->load();

//Retrive env variable
$SECRET_KEY = $_ENV['SECRET_KEY'];

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// retrieve required variables
$phone_number = $_POST['phone_number'];
$password = md5($_POST['password']);

// looking for the user in database
$sql = "SELECT * FROM user_table WHERE user_table.user_phonenumber=:phone_number";
$query = $con -> prepare($sql);
$query->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
$query->execute();

if($query->rowCount() === 0){

    $status = 203;
    $response = [
        "msg" => "Bad Request - User does not exist"
    ];

}else{

    // fetching row of that user
    $user = $query->fetchAll(PDO::FETCH_OBJ)[0];

    if($user->user_password === $password){

        // if user is authenticated then generate a token with JWT
        // $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify('+30 days')->getTimestamp();      // Add 60 seconds
        $serverName = "http://localhost/matkaApplicaton/backend/login";     // Retrieved from filtered POST data

        $data = [
            'iat'  => $issuedAt->getTimestamp(),         
            'iss'  => $serverName,                      
            'nbf'  => $issuedAt->getTimestamp(),         
            'exp'  => $expire,                           
            'user_phonenumber' => $user->user_phonenumber,       //User name
            'user_id' =>  $user->user_id,                 // User id
            'name' => $user->user_fullname               // User name             
        ];

        $jwt =  JWT::encode(
            $data,
            $SECRET_KEY,
            'HS512'
        );
        // sending jwt token to frontend with cookies
        setcookie("user_jwt", $jwt, time()+ (86400 * 30), "/","", 0); //86400*7 expiry time to 7 days

        $status = 200;
        $response = [
            "msg" => "User authenticated successfully",
            "user_name" => $user->user_fullname,
            "user_phonenumber" => $user->user_phonenumber,
            "profile_image" => $user->profile_image,
            "referral_code" => $user->referral_code
        ];

    }else{
        $status = 203;
        $response = [
            "msg" => "Unauthorized - Password does not match"
        ];
    }

}