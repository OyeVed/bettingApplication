<?php

// import db connection
require("dbcon.php");

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// retrieve required variables
$phone_number = $_POST['phone_number'];
$password = md5($_POST['password']);
$email = $_POST['email'];
$full_name = $_POST['full_name'];

//Finding is the particular user already signed up
$sql = "SELECT * FROM user_table WHERE user_table.user_phonenumber=:phone_number";
$query = $con -> prepare($sql);
$query->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
$query->execute();

if($query->rowCount() === 0){

    $sql = "INSERT INTO 
    user_table (user_phonenumber, user_password, user_email, user_fullname) VALUES 
    ( :user_phonenumber, :user_password, :user_email, :user_fullname)";
    $query = $con -> prepare($sql);
    $query->bindParam(':user_phonenumber', $phone_number, PDO::PARAM_STR);
    $query->bindParam(':user_password', $password, PDO::PARAM_STR);
    $query->bindParam(':user_email', $email, PDO::PARAM_STR);
    $query->bindParam(':user_fullname', $full_name, PDO::PARAM_STR);

    if($query->execute()){
        $user = $query->fetchAll(PDO::FETCH_OBJ);
        $status = 200;
        $response = [
            "msg" => "User created successfully",
            "user" => [
                "user_phone_number" => $phone_number,
                "user_password" => $password,
                "email" => $email
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