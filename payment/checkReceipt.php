<?php
session_start();
include("../include/dbconnection.php");

$code = $_GET['code'];

$q = mysql_query("SELECT * FROM loan_payment_details WHERE receipt_no = '".$code."'");
$get_q  = mysql_fetch_assoc($q);

if($get_q['receipt_no'] != '')
{
	$err_msg = "Receipt: ".$get_q['receipt_no']." is already exists!";
}
else
{
	$err_msg = "";	
}

echo $err_msg;
?>