<?php

// assign endpoints to their respective file location and allowed methods
$router->endpoint('login', './views/login', ['POST'], FALSE, ['username', 'password']);
$router->endpoint('signup', './views/signup', ['POST'], FALSE, ['username', 'password', 'email', 'phone']);

$router->endpoint('home', './views/home', ['GET'], FALSE, []);

$router->endpoint('fetch_profile', './views/fetch_profile', ['GET'], FALSE, ['username']);
$router->endpoint('profile', './views/profile', ['POST'], FALSE, ['fname','lname','username', 'email']);