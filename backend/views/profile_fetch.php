<?php

require("dbcon.php");
require('middleware.php');

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){
    // retrieve required variables
    $phone_number = $_POST['phone_number'];

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

        $user = $query->fetchAll(PDO::FETCH_OBJ)[0];

        if($user){
            $status = 200;
            $response = [
                "msg" => "User authenticated successfully",
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