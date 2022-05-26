<?php

require("dbcon.php");

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// retrieve required variables
$pana = $_POST['pana'];
$point = $_POST['point'];
$game = $_POST['game'];

$bid_list = [
    "pana" => $pana,
    "point" => $point,
    "game" => $game
];

$status = 200;
$response = [
    "bid_list" => $bid_list
];