<?php
/*
	Routes
	controller needs to be registered in dependency.php
*/

$app->get('/', 'App\Controllers\HomeController:dispatch')->setName('homepage');

$app->get('/home', 'App\Controllers\HomeController:dispatch')->setName('homepage');

$app->get('/users', 'App\Controllers\UserController:dispatch')->setName('userpage');

$app->get('/login', 'App\Controllers\LoginController:renderForm')->setName('login');

$app->post('/login', 'App\Controllers\LoginController:authenticateUser')->setName('login');

$app->get('/signup', 'App\Controllers\RegisterController:dispatch')->setName('signup');

$app->post('/signup', 'App\Controllers\RegisterController:dispatchSubmit')->setName('submit');

$app->get('/logout', 'App\Controllers\LogoutController:logout')->setName('logout');

$app->get('/createEvent', 'App\Controllers\EventController:dispatch')->setName('createEvent');

$app->post('/createEvent', 'App\Controllers\EventController:createEvent')->setName('createEvent');

$app->get('/event', 'App\Controllers\EventController:displayEventPage')->setName('event');

$app->get('/eventlist', 'App\Controllers\EventController:displayList')->setName('eventlist');

$app->post('/eventlist', 'App\Controllers\ResearchController:dispatch')->setName('search');

$app->post('/updateEventState', 'App\Controllers\EventController:changeEventState')->setName('updateEventState');

$app->post('/addEventTrial', 'App\Controllers\EventController:addEventTrial')->setName('addEventTrial');

$app->get('/profile', 'App\Controllers\ProfileController:displayProfile')->setName('profile');

$app->post('/modifyProfile', 'App\Controllers\ProfileController:modifyProfile')->setName('modifyProfile');

$app->get('/modifyProfile', 'App\Controllers\ProfileController:displayProfile')->setName('profile');

$app->get('/modifyPassword', 'App\Controllers\ProfileController:displayProfile')->setName('profile');

$app->post('/modifyPassword', 'App\Controllers\ProfileController:modifyPassword')->setName('modifyPassword');

$app->get('/participant', 'App\Controllers\ParticipantController:dispatch')->setName('participant');

$app->get('/submitparticipant', 'App\Controllers\ParticipantController:dispatc')->setName('submitparticipant');
