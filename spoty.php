<?php
function get_user($token) {
	$url = 'https://api.spotify.com/v1/me';

	$options = [
		CURLOPT_URL => $url,
		CURLOPT_HTTPHEADER => [
			'Authorization: Bearer ' . $token
		]
	];

	$ch = curl_init();
	curl_setopt_array($ch, $options);

	$ch_res = curl_exec($ch);

	return json_decode($ch_res);
}

function spoty__get_playback_history($token, $timestamp) {
	$url = 'https://api.spotify.com/v1/me/player/recently-played';

	$query_parameters = [
		'after' => $timestamp,
		'limit' => 10
	];

	$query_parameters_formated = http_build_query($query_parameters);

	$options = [
		CURLOPT_URL => $url . '?' . $query_parameters_formated,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTPHEADER => [
			'Authorization: Bearer ' . $token
		]
	];

	$ch = curl_init();
	curl_setopt_array($ch, $options);

	$ch_res = curl_exec($ch);
	return json_decode($ch_res);
}

function is_user($user_id): bool {
	$env_vars = parse_ini_file('.env');

	$server = $env_vars['SQL_SERVERNAME'];
	$dbname = $env_vars['SQL_DATABASE'];
	$conn_str = "mysql:host={$server};dbname={$dbname}";

	$username = $env_vars['SQL_USERNAME'];

	try {
	    $dbh = new PDO($conn_str, $username);
	} catch (PDOException $e) {
	    die('Could not connect: ' . $e->getMessage());
	}

	$stmt = $dbh->prepare('SELECT * FROM users WHERE user_id = ? LIMIT 1');
	$stmt->execute([$user_id]);
	$row = $stmt->fetch();

	return $row != false;
}

function save_user($user_id, $refresh_token): bool {
	$env_vars = parse_ini_file('.env');

	$server = $env_vars['SQL_SERVERNAME'];
	$dbname = $env_vars['SQL_DATABASE'];
	$conn_str = "mysql:host={$server};dbname={$dbname}";

	$username = $env_vars['SQL_USERNAME'];

	try {
	    $dbh = new PDO($conn_str, $username);
	} catch (PDOException $e) {
	    die('Could not connect: ' . $e->getMessage());
	}

	$stmt = $dbh->prepare('INSERT INTO users (user_id, refresh_token) VALUES (?, ?)');
	$result = $stmt->execute([$user_id, $refresh_token]);

	return $result;
}
?>