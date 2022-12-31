<?php

require('util.php');

$env_vars = parse_ini_file('.env');

$string = md5(mt_rand());
$state = base64_encode($string);

$query_parameters = [
	'client_id' => $env_vars['CLIENT_ID'],
	'response_type' => 'code',
	'redirect_uri' => $env_vars['REDIRECT_URI'],
	'state' => $state,
	'scope' => [
		'user-read-private user-read-email user-read-recently-played'
	]
];

$query_parameters_formated = http_build_query($query_parameters);

say($query_parameters_formated);

$authorization_url = 'https://accounts.spotify.com/authorize';

header('Location: ' . $authorization_url . '?' . $query_parameters_formated);
die();