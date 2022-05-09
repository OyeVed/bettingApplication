<?php session_start();

// import db connection
require("dbcon.php");

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// retrieve required variables
$username = $_POST['username'];
$password = md5($_POST['password']);

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

    if($user->user_password === $password){
        $status = 200;
        $response = [
            "msg" => "User authenticated successfully",
            "user" => [
                "firstname" => $user->first_name,
                "lastname" => $user->last_name,
                "username" => $user->user_username,
                "email" => $user->user_email,
                "phone" => $user->user_phone
            ]
        ];
        $_SESSION["username"] = $user->user_username;
        $_SESSION["password"] = $user->user_password;
    }else{
        $status = 203;
        $response = [
            "msg" => "Unauthorized - Password does not match"
        ];
    }

}