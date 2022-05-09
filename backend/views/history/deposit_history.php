<?php

require("dbcon.php");

$deposit_history = [
    [
        "serial_no" => "1",
        "date" => "08/05/2022",
        "amount" => "10,000",
        "transaction_id" => "137X2588WQD55"
    ]
];


$status = 200;

$response = [
    "deposit_history" => $deposit_history
];