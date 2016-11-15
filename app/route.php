<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

$app->get('/', 'App\Controllers\HomeController:dispatch')->setName('homepage');

$app->get('/users', 'App\Controllers\UserController:dispatch')->setName('userpage');

$app->get('/login', 'App\Controllers\LoginController:form')->setName('login');

$app->post('/login', 'App\Controllers\LoginController:logIn')->setName('login');