<?php

require('middleware.php');

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){
    setcookie("jwt", $token, time()-3600);    

    $status = 200;
    
    $response = [
        "msg" => "Logged out successfully"
    ];
}

