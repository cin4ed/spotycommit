<?php

require('util.php');
require('oauth.php');

// GET /
if (request('GET', '/')) {

	session_start();

	if (isset($_SESSION['refresh_token'])) {
		header('Location /app.php');
	} else {
		header('Location /home.php');
	}

	exit();
}

// GET /auth
if (request('GET', '/auth')) {
	redirect_to_auth();
}

// GET /callback
if (request('GET', '/callback')) {
	$env_vars = parse_ini_file('.env');

	$state = $_GET['state'];

	session_start();
	$stored_state = $_SESSION['state'];

	// TODO: better handle this errors and display appropiate message in frontend
	// send error to front end too
	if ($state != $stored_state) {
		die('state mismatch!'); // TODO: change for response
	}

	if (isset($_GET['error'])) {
		die("error occurred: {$_GET['error']}"); // TODO: change for response
	}

	$query_parameters = [
		'grant_type' => 'authorization_code',
		'code' => $_GET['code'], // <----------------------- CODE
		'redirect_uri' => $env_vars['REDIRECT_URI']
	];

	$query_parameters_formated = http_build_query($query_parameters);

	// Authorization header
	$auth_str = $env_vars['CLIENT_ID'] . ':' . $env_vars['CLIENT_SECRET'];
	$auth_code = base64_encode($auth_str);

	// Request options
	$options = [
		CURLOPT_URL => 'https://accounts.spotify.com/api/token',
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS => $query_parameters_formated,
		CURLOPT_HTTPHEADER => [
			'Content-type: application/x-www-form-urlencoded',
			'Authorization: Basic ' . $auth_code
		]
	];

	// Execute request
	$ch = curl_init();
	curl_setopt_array($ch, $options);

	// Get token from response
	$ch_res = curl_exec($ch);
	$ch_res_arr = json_decode($ch_res, true);

	// Validate token
	if (isset($ch_res_arr['error']) && $ch_res_arr['error'] == 'invalid_grant') {
		die("invalid_grant"); // Change for response
	}

	$access_token = $ch_res_arr['access_token'];
	$refresh_token = $ch_res_arr['refresh_token'];

	// Save token in session
	session_start();
	$_SESSION['access_token'] = $access_token;
	$_SESSION['refresh_token'] = $refresh_token;

	header('Location: /app.php');
	exit();
}
