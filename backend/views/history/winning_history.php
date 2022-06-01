<?php

require("dbcon.php");

$winning_history = [
    [
        "serial_no" => "1",
        "market" => "Main Baazar",
        "date" => "08/05/2022",
        "win_no" => "5",
        "amount" => "90"

    ]
];


$status = 200;

$response = [
    "winning_history" => $winning_history
];