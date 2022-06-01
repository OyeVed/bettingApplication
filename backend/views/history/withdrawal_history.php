<?php

require("dbcon.php");

$withdrawal_history = [
    [
        "serial_no" => "1",
        "date" => "08/05/2022",
        "amount" => "10,000",
        "transaction_id" => "137X2588WQD55"
    ]
];


$status = 200;

$response = [
    "withdrawal_history" => $withdrawal_history
];