<?php

// assign endpoints to their respective file location and allowed methods
$router->endpoint('login', './views/login', ['POST'], FALSE, ['username', 'password']);
$router->endpoint('signup', './views/signup', ['POST'], FALSE, ['username', 'password', 'email', 'phone']);

$router->endpoint('home', './views/home', ['GET'], FALSE, []);

$router->endpoint('profile_fetch', './views/profile_fetch', ['GET'], FALSE, ['username']);
$router->endpoint('profile_save', './views/profile_save', ['POST'], FALSE, ['fname','lname','username', 'email']);

$router->endpoint('single_digit', './views/games/single_digit', ['POST'], FALSE, ['pana', 'point', 'game']);
$router->endpoint('double_digit', './views/games/double_digit', ['POST'], FALSE, ['pana', 'point', 'game']);
$router->endpoint('triple_digit', './views/games/triple_digit', ['POST'], FALSE, ['pana', 'point', 'game']);
$router->endpoint('single_panna', './views/games/single_panna', ['POST'], FALSE, ['pana', 'point', 'game']);
$router->endpoint('double_panna', './views/games/double_panna', ['POST'], FALSE, ['pana', 'point', 'game']);
$router->endpoint('triple_panna', './views/games/triple_panna', ['POST'], FALSE, ['pana', 'point', 'game']);
$router->endpoint('half_sangam', './views/games/half_sangam', ['POST'], FALSE, ['pana', 'point', 'game']);
$router->endpoint('full_sangam', './views/games/full_sangam', ['POST'], FALSE, ['pana', 'point', 'game']);

$router->endpoint('game_rate', './views/game_rate', ['GET'], FALSE, []);
$router->endpoint('reset_password', './views/reset_password', ['POST'], FALSE, ['old_password', 'new_password', 'confirm_password']);


$router->endpoint('bid_history', './views/history/bid_history', ['GET'], FALSE, []);
$router->endpoint('deposit_history', './views/history/deposit_history', ['GET'], FALSE, []);
$router->endpoint('winning_history', './views/history/winning_history', ['GET'], FALSE, []);
$router->endpoint('withdrawal_history', './views/history/withdrawal_history', ['GET'], FALSE, []);
$router->endpoint('statement', './views/history/statement', ['GET'], FALSE, []);
