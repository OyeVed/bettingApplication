<?php

require("dbcon.php");
require("middleware.php");

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){
    $game_rate = [];
    $sql = "SELECT * FROM game_rate_table";
    $query = $con -> prepare($sql);
    $query->execute();
    if($query->execute() && $query->rowCount() != 0){
        $games = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($games as $game){
            $details = [
                "game_name" => $game->game_name,
                "game_rate" => $game->game_rate
            ];
            array_push($game_rate, $details);
            $status = 200;
            $response = [
                "game_rate" => $game_rate
            ];
        }
    }else{
        $status = 203;
        $response = [
            "msg" => "Internal Server Error - Data can't be fetched"
        ];
    }
}


// $game_rate = [
//     [
//         "game" => "Main Baazar",
//         "minimum_bid_price" => "10",
//         "winning_price" => "90"
//     ]
// ];
