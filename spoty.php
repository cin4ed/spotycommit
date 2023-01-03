<?php
function is_user(): bool {
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	$user_id = $_SESSION['spotify_user_id'] ?? NULL;

	if (!$user_id) {
		return false;
	}

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
?>