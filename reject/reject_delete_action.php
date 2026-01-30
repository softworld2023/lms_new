<?php
session_start();
$conn_hostname = "localhost";
$conn_database = "loansystem";


$conn_username = "root";
$conn_password = "";
$conn_connection = mysql_connect($conn_hostname , $conn_username , $conn_password ) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($conn_database, $conn_connection);
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");



if($_POST['action'] == 'delete_reject')
{
	$id = $_POST['id'];
	$sql = mysql_query("UPDATE reject_loan SET status = 'Deleted' WHERE id = '".$id."'");

	}
?>