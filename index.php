<?php

$env_vars = parse_ini_file('.env');

function request($method, $path) {
	$request_uri = $_SERVER['REQUEST_URI'];
	$url = parse_url($request_uri);
	return ($_SERVER['REQUEST_METHOD'] == $method && $url['path'] == $path);
}

// GET /login
if (request('GET', '/login')) {
	$authorization_url = 'https://accounts.spotify.com/authorize';

	$data = array(
	    'client_id' => $env_vars['CLIENT_ID'],
	    'redirect_uri' => $env_vars['REDIRECT_URI'],
	    'scope' => 'user-read-private user-read-email user-read-recently-played',
	    'response_type' => 'code',
	    'state' => 'abcdefg'
	);

	$query_string = http_build_query($data);
	$redirect_auth_url = $authorization_url . '?' . $query_string;

	header('Location: ' . $redirect_auth_url);
	exit;
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<a href="/login">Authorize</a>
</body>
</html>