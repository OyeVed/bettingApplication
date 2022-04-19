<?php

// assign endpoints to their respective file location and allowed methods
$router->endpoint('login', './login', ['POST'], FALSE, ['username', 'password']);
$router->endpoint('signup', './signup', ['POST'], FALSE, ['username', 'password', 'email', 'phone']);