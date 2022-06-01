<?php

require("dbcon.php");

$statement = [
    [
        "serial_no" => "1",
        "date" => "08/05/2022",
        "amount" => "10,000",
        "transaction_id" => "137X2588WQD55",
        "transaction_type" => "deposit"

    ],
    [
        "serial_no" => "2",
        "date" => "12/05/2022",
        "amount" => "11,543",
        "transaction_id" => "137X9763WQD55",
        "transaction_type" => "withdrawal"

    ]    
];


$status = 200;

$response = [
    "statement" => $statement
];