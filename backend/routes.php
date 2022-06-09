<?php

// assign endpoints to their respective file location and allowed methods

//login, logout, reset_password, middleware, image_upload and signup routes
$router->endpoint('login', './views/login', ['POST'], FALSE, ['phone_number', 'password']);
$router->endpoint('signup', './views/signup', ['POST'], FALSE, ['phone_number', 'password', 'email', 'full_name']);
$router->endpoint('logout', './views/logout', ['POST'], FALSE, ['phone_number']);
$router->endpoint('reset_password', './views/reset_password', ['POST'], FALSE, ['old_password', 'new_password']);
$router->endpoint('middleware', './middleware.php', ['POST'], FALSE, []);
$router->endpoint('image_upload', './views/image_upload', ['POST'], FALSE, []);


//home route
$router->endpoint('home', './views/home', ['GET'], FALSE, []);

//profile saving and fetching route
$router->endpoint('profile_fetch', './views/profile_fetch', ['POST'], FALSE, ['phone_number']);
$router->endpoint('profile_save', './views/profile_save', ['POST'], FALSE, ['phone_number', 'email', 'full_name']);


//route to place bids
$router->endpoint('save_bid', './views/save_bid', ['POST'], FALSE, ['game_name', 'market_id', 'user_id', 'bid_lists']);


//route for fetching game rate i.e. how much money you win if you play with these much amount
$router->endpoint('game_rate', './views/game_rate', ['GET'], FALSE, []);


//user history fetching routes
$router->endpoint('bid_history', './views/history/bid_history', ['GET'], FALSE, []);
$router->endpoint('deposit_history', './views/history/deposit_history', ['GET'], FALSE, []);
$router->endpoint('winning_history', './views/history/winning_history', ['GET'], FALSE, []);
$router->endpoint('withdrawal_history', './views/history/withdrawal_history', ['GET'], FALSE, []);
$router->endpoint('statement', './views/history/statement', ['GET'], FALSE, []);


//payment routes add_money, withdraw_money and wallet_balance
$router->endpoint('add_money', './views/add_money.php', ['POST'], FALSE, ['user_id']);
$router->endpoint('withdraw_money', './views/withdraw_money.php', ['POST'], FALSE, ['user_id']);
$router->endpoint('wallet_balance', './views/wallet_balance', ['GET'], FALSE, []);