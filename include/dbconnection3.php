<?php
//session_start();
error_reporting(0);
// zoe 
//$conn_hostname = "25.19.183.108:53306"; 

// reaching capital
//$conn_hostname = "25.40.164.135:53306"; 

// master-pc  
//$conn_hostname = "25.23.124.85:53306";

//popular
//$conn_hostname = "25.13.88.68:53306";

//easy
//$conn_hostname = "25.39.66.195:53306";

//paolyta
//$conn_hostname = "25.37.139.228:53306";

//westfree
//$conn_hostname = "25.45.65.117:53306";

//reaching marketing
//$conn_hostname = "25.16.198.140:53306";

//wereliance
//$conn_hostname = "25.38.127.134:53306";

//kl hq
//$conn_hostname = "25.42.219.34:53306";


/*$conn_database = "goe";
$conn_username = "softworld";
$conn_password = "softworld";
$conn_connection = mysql_connect($conn_hostname , $conn_username , $conn_password ) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($conn_database, $conn_connection);*/

//local connection
$conn_hostname = "localhost";

if($_SESSION['login_database_specific'] != '')
{
	//it will stay here because the other page's "dbconnection.php" already have this variables!
	$conn_database = $_SESSION['login_database_specific'];
}else
{
	$conn_database = "anseng_user";
	$_SESSION['login_database_specific'] = $conn_database;
}
$conn_username = "root";
$conn_password = "";
$conn_connection = mysql_connect($conn_hostname , $conn_username , $conn_password ) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($conn_database, $conn_connection);
?>