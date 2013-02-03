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
	
	$user = new stdClass;
	$user->type  = 'user';
	$user->name  = $app->form('name');
	$user->email = $app->form('email');

	echo json_encode($user);

	// Add an entry to our verge counhDB via curl
	$curl = curl_init();
	// curl options
	$options = array(
			CURLOPT_URL				=> 'localhost:5984/verge',
			CURLOPT_POSTFIELDS		=> json_encode($user),
			CURLOPT_HTTPHEADER		=> array('Content-Type: application/json'),
			CURLOPT_CUSTOMREQUEST	=> 'POST',
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_ENCODING		=> 'utf-8',
			CURLOPT_HEADER			=> false,
			CURLOPT_FOLLOWLOCATION	=> true,
			CURLOPT_AUTOREFERER		=> true,
		);
	curl_setopt_array($curl, $options);
	curl_exec($curl);
	curl_close($curl);


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