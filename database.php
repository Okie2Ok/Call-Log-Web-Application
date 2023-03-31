<?php
// Connect to database
	$host = "localhost";
	$user = "root";
	$password = "";
	$dbname = "call_log";

	$conn = mysqli_connect($host, $user, $password, $dbname);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
?>