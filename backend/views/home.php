<?php

require("dbcon.php");
require("middleware.php");

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){
    $game_list = [];
    $sql = "SELECT * FROM market_table";
    $query = $con -> prepare($sql);
    $query->execute();
    if($query->execute() && $query->rowCount() != 0){
        $markets = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($markets as $market) {
            $status = "ok";
            $details = [
                "market_fullname" => $market->market_fullname,
                "market_opentime" => $market->market_opentime,
                "market_closetime" => $market->market_closetime,
                "status" => $status,
                "result" => "good"
            ];
            array_push($game_list, $details);
            $status = 200;
            $response = [
                "game_list" => $game_list
            ];
        }
    }else{
        $status = 203;
        $response = [
            "msg" => "Internal Server Error - Data can't be fetched"
        ];
    }
}


































// $game_list = [
//     [
//         "game" => "Main Baazar",
//         "game_status" => "Open",
//         "open_time" => "9:40pm",
//         "close_time" => "11:59pm",
//         "result" => "xxx-xx-xxx"
//     ],
//     [
//         "game" => "Milan Day",
//         "game_status" => "Close",
//         "open_time" => "9:40pm",
//         "close_time" => "11:59pm",
//         "result" => "xxx-xx-xxx"
//     ],
//     [
//         "game" => "Milan Night",
//         "game_status" => "Cpen",
//         "open_time" => "9:40pm",
//         "close_time" => "11:59pm",
//         "result" => "xxx-xx-xxx"
//     ],
//     [
//         "game" => "Time Baazar",
//         "game_status" => "Open",
//         "open_time" => "9:40pm",
//         "close_time" => "11:59pm",
//         "result" => "xxx-xx-xxx"
//     ],
// ];


// $status = 200;

// $response = [
//     "game_list" => $game_list
// ];