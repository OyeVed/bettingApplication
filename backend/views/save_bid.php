<?php

require("dbcon.php");
require('middleware.php');
require_once('../vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// retrieve request data
$_POST = json_decode(file_get_contents("php://input"), true);

// getting token from cookie
$token = $_COOKIE["jwt"];

// checking is the user authorized 
if(auth($token)){
    //Total deposit on a particular day
    $secret_key = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
    $payload = JWT::decode($token, new Key($secret_key, 'HS512'));

    //taking parameters from post request
    $game_name = $_POST['game_name'];
    $user_id = $_POST['user_id'];
    $market_id = $_POST['market_id'];
    $bid_lists = $_POST['bid_lists'];

    //calculating bid amount
    $total_amount = 0;
    foreach($bid_lists as $bid_list){
        $total_amount = $total_amount + $bid_list['points'];
    }

    //calculating funds available
    $query = $con->prepare("
        SELECT
        amount_in_wallet
        FROM transaction_details
        WHERE user_id=:user_id
        ORDER BY transaction_id DESC
        LIMIT 1
    ");
    $query->bindParam(':user_id', $payload->user_id, PDO::PARAM_STR);
    if($query->execute()){
        $query->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $query->fetch()) {
            $available_amount = $row['amount_in_wallet'];
        }
        if(!isset($available_amount)){
            $status = 203;
            $response = [
                "msg" => "No transactions found."
            ];
        }else{
            //checking are funds available to place bet
            if($available_amount >= $total_amount){
                //updating the transaction_details table
                $available_amount -= $total_amount;
                $transaction_type = "withdrawal";
                $transaction_name = "bets placed";
                $sql = "INSERT INTO transaction_details ( user_id, transaction_type, transaction_name, transaction_amount, amount_in_wallet) VALUES
                (:user_id, :transaction_type, :transaction_name, :transaction_amount, :amount_in_wallet)";
                $query = $con->prepare($sql);
                $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                $query->bindParam(':transaction_amount', $total_amount, PDO::PARAM_STR);
                $query->bindParam(':amount_in_wallet', $available_amount, PDO::PARAM_STR);
                $query->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
                $query->bindParam(':transaction_name', $transaction_name, PDO::PARAM_STR);
                $query->execute();
                foreach($bid_lists as $bid_list){
                    //Updating to betting_history table
                    $number = $bid_list['number']; 
                    $points = $bid_list['points'];
                    $game_type = $bid_list['game_type'];
                    $sql = "INSERT INTO betting_history ( user_id, market_id, game_name, number, points, game_type) VALUES
                    (:user_id, :market_id, :game_name, :number, :points, :game_type)";
                    $query = $con->prepare($sql);
                    $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                    $query->bindParam(':market_id', $market_id, PDO::PARAM_STR);
                    $query->bindParam(':game_name', $game_name, PDO::PARAM_STR);
                    $query->bindParam(':number', $number, PDO::PARAM_STR);
                    $query->bindParam(':points', $points, PDO::PARAM_STR);
                    $query->bindParam(':game_type', $game_type, PDO::PARAM_STR);
                    if($query->execute()){
                        $status = 200;
                        $response = [
                            "msg" => "Bids placed successfully."
                        ];
                    }else{
                        $status = 203;
                        $response = [
                            "msg" => "Internal Sever Error."
                        ];            
                    }
                }
            }else{
                $status = 203;
                $response = [
                    "msg" => "Insufficient funds."
                ];
            }
        }
        
    }else{
        $status = 203;
        $response = [
            "msg" => "No user transactions found."
        ];
    }
    
};