<?php
function is_user(): bool {
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