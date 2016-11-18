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

$app->post('/submitparticipant', 'App\Controllers\ParticipantController:dispatchSubmit')->setName('submitparticipant');

$app->get('/download', 'App\Controllers\CsvController:downloadParticipants')->setName('download');

$app->post('/upload', 'App\Controllers\CsvController:uploadResults')->setName('upload');

$app->get('/upload', 'App\Controllers\CsvController:uploadResults')->setName('homepage');

$app->post('/generateURL', 'App\Controllers\EventController:simplifyURL')->setName('generateURL');

$app->get('/seeResults', 'App\Controllers\ParticipantController:findResults')->setName('seeResults');

$app->get('/results', 'App\Controllers\EventController:displayResults')->setName('results');
