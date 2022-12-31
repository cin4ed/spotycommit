<?php

function say($message) {
	echo $message . '<br />';
}

function request($method, $path) {
	$request_uri = $_SERVER['REQUEST_URI'];
	$url = parse_url($request_uri);
	return ($_SERVER['REQUEST_METHOD'] == $method && $url['path'] == $path);
}
