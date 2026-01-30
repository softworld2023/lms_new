<?php

$conn_hostname = "localhost";
//$conn_database = "loansystem";
$conn_username = "root";
$conn_password = "";


@$db1 = mysql_connect($conn_hostname, $conn_username, $conn_password); 
@$db2 = mysql_connect($conn_hostname, $conn_username, $conn_password, true);

mysql_select_db('anseng_user', $db1);
 mysql_select_db('loansystem2', $db2); 
 /* //Then to query database 1, do this:

mysql_query('select * from customer_id', $db1); 
//and for database 2:

mysql_query('select * from customer_details', $db2); */

?>