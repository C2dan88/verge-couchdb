<?php

include 'lib/bones.php';

get('/', function($app) {
	$app->set('message', 'Welcome Back!');
	$app->render('home');
});

get('/signup', function($app) {
	$app->render('signup');
});

post('/signup', function($app) {
	
	$user = new User();
	$user->name  = $app->form('name');
	$user->email = $app->form('email');

	// Add an entry to our verge couchDB with Sag
	$app->couch->post($user->to_json());


	$app->set('message', 'Thanks for Signin Up ' . $app->form('name') . '!');
	$app->render('home');
});

get('/say/:message', function($app) {
	$app->set('message', $app->request('message'));
	$app->render('home');
});

echo '<hr /><pre>' . print_r([
	'QUERY_STRING' => $_SERVER['QUERY_STRING'],
	'REQUEST_URI' => $_SERVER['REQUEST_URI'],
	'POST' => $_POST,
	'GET' => $_GET,
	//[$_SERVER],
	]
	, true) . 
'</pre>';