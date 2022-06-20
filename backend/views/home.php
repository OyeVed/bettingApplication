<?php

require("dbcon.php");
require("middleware.php");

// getting token from cookie
$token = $_COOKIE["user_jwt"];

// checking is the user authorized 
if(auth($token)){
    $game_list = [];
    $sql = "SELECT * FROM market_table";
    $query = $con -> prepare($sql);
    $query->execute();
    if($query->execute() && $query->rowCount() != 0){
        $markets = $query->fetchAll(PDO::FETCH_OBJ);
        foreach ($markets as $market) {
            if(date($market->market_closetime) > date("H-i-s")){
                $status = "open";
            }else{
                $status = "close";
            }
            $details = [
                "market_id" => $market->market_id,
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
