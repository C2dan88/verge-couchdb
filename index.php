<?php

include 'lib/bones.php';

get('/', function($app) {
	echo "Home";
});

get('/signup', function($app) {
	echo 'Signup!';
});

echo '<hr /><pre>' . print_r([
								'QUERY_STRING' => $_SERVER['QUERY_STRING'],
								'REQUEST_URI' => $_SERVER['REQUEST_URI'],
							 ]
							 , true) . '</pre>';