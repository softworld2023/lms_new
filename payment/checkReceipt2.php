<?php
session_start();
include("../include/dbconnection.php");

$code = $_GET['code'];
$loanid = $_GET['loanid'];
$month = $_GET['month'];

$cust_q = mysql_query("SELECT * FROM customer_loanpackage 
						WHERE id = '".$loanid."'");
$cust = mysql_fetch_assoc($cust_q);

$q = mysql_query("SELECT * FROM loan_payment_details 
					WHERE receipt_no = '".$code."' 
					AND customer_loanid != '".$loanid."' 
					AND month_receipt = '".$month."' 
					AND branch_id = '".$cust['branch_id']."'");

$get_q  = mysql_fetch_assoc($q);

if($get_q['receipt_no'] != '')
{//means it does have result... have same receipt_no
	$err_msg = "Receipt: ".$get_q['receipt_no']." is already exists!";
}
else
{
	$err_msg = "";	
}

echo $err_msg;
?>