<?php

// assign endpoints to their respective file location and allowed methods
$router->endpoint('login', './views/login', ['POST'], FALSE, ['phone_number', 'password']);
$router->endpoint('signup', './views/signup', ['POST'], FALSE, ['phone_number', 'password', 'email', 'full_name', 'withdrawal_method']);
$router->endpoint('logout', './views/logout', ['POST'], FALSE, ['phone_number']);

$router->endpoint('home', './views/home', ['GET'], FALSE, []);

$router->endpoint('profile_fetch', './views/profile_fetch', ['GET'], FALSE, ['phone_number']);
$router->endpoint('profile_save', './views/profile_save', ['POST'], FALSE, ['phone_number', 'password', 'email', 'full_name', 'withdrawal_method']);

$router->endpoint('save_bid', './views/save_bid', ['POST'], FALSE, ['game_name', 'market_id', 'user_id', 'bid_lists']);

$router->endpoint('game_rate', './views/game_rate', ['GET'], FALSE, []);
$router->endpoint('reset_password', './views/reset_password', ['POST'], FALSE, ['old_password', 'new_password', 'confirm_password']);


$router->endpoint('bid_history', './views/history/bid_history', ['GET'], FALSE, []);
$router->endpoint('deposit_history', './views/history/deposit_history', ['GET'], FALSE, []);
$router->endpoint('winning_history', './views/history/winning_history', ['GET'], FALSE, []);
$router->endpoint('withdrawal_history', './views/history/withdrawal_history', ['GET'], FALSE, []);
$router->endpoint('statement', './views/history/statement', ['GET'], FALSE, []);


$router->endpoint('middleware', './middleware.php', ['POST'], FALSE, []);

$router->endpoint('add_money', './views/add_money.php', ['POST'], FALSE, ['user_id']);
$router->endpoint('withdraw_money', './views/withdraw_money.php', ['POST'], FALSE, ['user_id']);