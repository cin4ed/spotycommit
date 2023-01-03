<?php

function is_user(): boolean {
	$user = $_SESSION['spotify_user_id'];
	return isset($user);
}


?>