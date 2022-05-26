<?php

require("dbcon.php");

$bid_history = [
    [
        "serial_no" => "1",
        "market" => "Main Baazar",
        "date" => "08/05/2022",
        "type" => "Single Digit",
        "digit" => "5",
        "points" => "10"
    ]
];


$status = 200;

$response = [
    "bid_history" => $bid_history
];