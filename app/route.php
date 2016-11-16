<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

$app->get('/', 'App\Controllers\HomeController:dispatch')->setName('homepage');

$app->get('/home', 'App\Controllers\HomeController:dispatch')->setName('homepage');

$app->get('/users', 'App\Controllers\UserController:dispatch')->setName('userpage');

$app->get('/login', 'App\Controllers\LoginController:form')->setName('login');

$app->post('/login', 'App\Controllers\LoginController:authenticateUser')->setName('login');

$app->get('/signup', 'App\Controllers\RegisterController:dispatch')->setName('signup');

$app->post('/signup', 'App\Controllers\RegisterController:dispatchSubmit')->setName('submit');

$app->get('/logout', 'App\Controllers\LogoutController:logout')->setName('logout');
