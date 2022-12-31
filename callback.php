<?php

require('util.php');

$env_vars = parse_ini_file('.env');

$state = $_GET['state'];

session_start();
$stored_state = $_SESSION['state'];

if ($state != $stored_state) {
	die('state mismatch!');
}

if (isset($_GET['error'])) {
	die("error occurred: {$_GET['error']}");
}

$query_parameters = [
	'grant_type' => 'authorization_code',
	'code' => $_GET['code'],
	'redirect_uri' => $env_vars['REDIRECT_URI']
];

$query_parameters_formated = http_build_query($query_parameters);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $query_parameters_formated);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Content-type: application/x-www-form-urlencoded',
	'Authorization: Basic ' . base64_encode($env_vars['CLIENT_ID'] . ':' . $env_vars['CLIENT_SECRET'])
]);
$ch_res = curl_exec($ch);
$ch_res_arr = json_decode($ch_res, true);

if (isset($ch_res_arr['error']) && $ch_res_arr['error'] == 'invalid_grant') {
	header('Location: /auth.php');
	die();
}

$access_token = $ch_res_arr['access_token'];
$refresh_token = $ch_res_arr['refresh_token'];

session_start();
$_SESSION['access_token'] = $access_token;
$_SESSION['refresh_token'] = $refresh_token;

header('Location: /app.php');
die();