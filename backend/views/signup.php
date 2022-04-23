<?php

// import db connection
require("dbcon.php");

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// retrieve required variables
$username = $_POST['username'];
$password = md5($_POST['password']);
$email = $_POST['email'];
$phone = $_POST['phone'];

$sql = "SELECT * FROM user_table WHERE user_table.user_email=:email OR user_table.user_username=:username";
$query = $con -> prepare($sql);
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->bindParam(':username', $username, PDO::PARAM_STR);
$query->execute();

if($query->rowCount() === 0){

    $sql = "INSERT INTO user_table (user_username, user_password, user_email, user_phone) VALUES (:username, :password, :email, :phone)";
    $query = $con -> prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':phone', $phone, PDO::PARAM_STR);

    if($query->execute()){
        $user = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => "User created successfully",
            "user" => [
                "username" => $username,
                "email" => $email,
                "phone" => $phone
            ]
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Internal Server Error - User creation failed"
        ];
    }

}else{

    $status = 203;
    $response = [
        "msg" => "Bad Request - User already exists"
    ];

}