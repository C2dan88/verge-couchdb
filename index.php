<?php

include 'lib/bones.php';

get('/', function($app) {
	$app->set('message', 'Welcome Back!');
	$app->render('home');
});

get('/signup', function($app) {
	$app->render('Signup');
});

echo '<hr /><pre>' . print_r([
								'QUERY_STRING' => $_SERVER['QUERY_STRING'],
								'REQUEST_URI' => $_SERVER['REQUEST_URI'],
							 ]
							 , true) . '</pre>';