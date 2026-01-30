<?php
    // ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);
	
	date_default_timezone_set('Asia/Kuala_Lumpur');

	// Database connection settings
	$host = 'localhost';
	$username = 'root';
	$password = '';

	//MySQL connection
	$con = mysql_connect($host, $username, $password);

	$db_suffix = '';	// uncomment this line for production
	// $db_suffix = '_dev';	// uncomment this line for development
?>
