<?php

session_start();
include("../include/dbconnection.php");
include("../config.php");
$id = 1790;

$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql2_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND payment_date = '0000-00-00' ORDER BY month ASC");
	$get_q2 = mysql_fetch_assoc($sql2_q);
	
	$bal_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND balance != 0 ORDER BY balance ASC");
	$get_b = mysql_fetch_assoc($bal_q);
	
	$month_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' ORDER BY month DESC");
	$newmonth_q = mysql_fetch_assoc($month_q);
	
	//full interest
	$fullint = $get_q['total_loan'] * ($get_q2['int_percent'] / 100);
	echo $id;
	echo $get_q['total_loan'];
	echo $fullint;
?>