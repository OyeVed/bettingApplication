<?php

require("dbcon.php");

$game_rate = [
    [
        "game" => "Main Baazar",
        "minimum_bid_price" => "10",
        "winning_price" => "90"
    ]
];


$status = 200;

$response = [
    "game_rate" => $game_rate
];