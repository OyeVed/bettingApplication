<?php

// getting token from cookie
$token = $_COOKIE["user_jwt"];


setcookie("jwt", $token, time()-3600);    

$status = 200;

$response = [
    "msg" => "Logged out successfully"
];


