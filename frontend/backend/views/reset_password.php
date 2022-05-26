<?php

require("dbcon.php");
require('middleware.php');

$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){
    $phone_number = "1234567890";
    $old_password = md5($_POST['old_password']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
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
    
        if($user->user_password === $old_password  && $new_password === $confirm_password){
            $new_password = md5($new_password);
            $sql = "UPDATE user_table SET user_password=:new_password WHERE user_id=4";
            $query = $con -> prepare($sql);
            $query->bindParam(':new_password', $new_password, PDO::PARAM_STR);
            $status = 200;
            $response = [
                "msg" => "Password updated successfully",
            ];
        }else{
            $status = 203;
            $response = [
                "msg" => "Unauthorized - Password does not match"
            ];
        }
    
    }
}



//body parameters
// {
    // "old_password" : "admin",
    // "new_password" : "admin",
    // "confirm_password" : "admin"
// }