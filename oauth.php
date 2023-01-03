<?php
function redirect_to_auth() {
	$env_vars = parse_ini_file('.env');

	$string = md5(mt_rand());
	$state = base64_encode($string);

	// store state in session for validation in callback.php
	session_start();
	$_SESSION['state'] = $state;

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

	$authorization_url = 'https://accounts.spotify.com/authorize';

	header('Location: ' . $authorization_url . '?' . $query_parameters_formated);
	exit();
}
?>