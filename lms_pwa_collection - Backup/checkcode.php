<?php
session_start();
include("../include/dbconnection.php");

$code2 = $_GET['code2'];

$q = mysql_query("SELECT * FROM customer_details WHERE customercode2 = '".$code2."' AND branch_id = '".$_SESSION['login_branchid']."'");
$get_user  = mysql_fetch_assoc($q);

if($get_user['customercode2'] != '')
{
	$err_msg = "Customer with code: ".$get_user['customercode2']." is already exists!";
}
else
{
	$err_msg = "";	
}

echo $err_msg;
?>