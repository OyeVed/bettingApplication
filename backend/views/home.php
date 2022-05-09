<?php

require("dbcon.php");

$game_list = [
    [
        "game" => "Main Baazar",
        "game_status" => "Open",
        "open_time" => "9:40pm",
        "close_time" => "11:59pm",
        "result" => "xxx-xx-xxx"
    ],
    [
        "game" => "Milan Day",
        "game_status" => "Close",
        "open_time" => "9:40pm",
        "close_time" => "11:59pm",
        "result" => "xxx-xx-xxx"
    ],
    [
        "game" => "Milan Night",
        "game_status" => "Cpen",
        "open_time" => "9:40pm",
        "close_time" => "11:59pm",
        "result" => "xxx-xx-xxx"
    ],
    [
        "game" => "Time Baazar",
        "game_status" => "Open",
        "open_time" => "9:40pm",
        "close_time" => "11:59pm",
        "result" => "xxx-xx-xxx"
    ],
];


$status = 200;

$response = [
    "game_list" => $game_list
];