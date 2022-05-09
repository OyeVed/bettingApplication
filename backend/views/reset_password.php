<?php session_start();
require("dbcon.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$username = "admin";
$old_password = md5($_POST['old_password']);
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

$sql = "SELECT * FROM user_table WHERE user_table.user_username=:username";
$query = $con -> prepare($sql);
$query->bindParam(':username', $username, PDO::PARAM_STR);
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
        $sql = "INSERT INTO user_table (user_password) VALUES (:password)";
        $query = $con -> prepare($sql);
        $query->bindParam(':password', $new_password, PDO::PARAM_STR);
        $status = 200;
        $response = [
            "msg" => "Password updated successfully",
            // "user" => [
            //     "password" => $user->user_password
            // ]
        ];
        $_SESSION["password"] = $user->user_password;
    }else{
        $status = 203;
        $response = [
            "msg" => "Unauthorized - Password does not match"
        ];
    }

}

//body parameters
// {
//     "old_password" : "admin",
//     "new_password" : "admin",
//     "confirm_password" : "admin"
// }