<?php

require("dbcon.php");
require('middleware.php');
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

use Dotenv\Dotenv;

// Looing for .env at the root directory
$dotenv = Dotenv::createImmutable('./');
$dotenv->load();

//Retrive env variable
$SECRET_KEY = $_ENV['SECRET_KEY'];

// getting token from cookie
$token = $_COOKIE["user_jwt"];

// checking is the user authorized 
if(auth($token)){
    $payload = JWT::decode($token, new Key($SECRET_KEY, 'HS512'));
    $total_bid_amount = 0;

    //query
    $sql = "SELECT bh.game_name, mt.market_fullname, bh.game_type, bh.number, bh.points, bh.created_at
    FROM betting_history as bh JOIN market_table as mt
    ON bh.market_id = mt.market_id 
    WHERE bh.user_id=:user_id ORDER BY bh.bet_id desc";
    $query = $con -> prepare($sql);
    $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
    if($query->execute()){
        $bid_history = $query->fetchAll(PDO::FETCH_OBJ);
        foreach($bid_history as $bet){
            $total_bid_amount =+ $bet->points;
        }
        $status = 200;
        $response = [
            "msg" => "Bid history fetched successfully",
            "total_bid_amount" => $total_bid_amount,
            "bid_history" => $bid_history
        ];
    }else{
        $status = 203;
        $response = [
            "msg" => "Bets can't be fetched"
        ];
    }
}
