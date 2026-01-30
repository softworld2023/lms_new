<?php
session_start();
include("../include/dbconnection.php");

$code = $_GET['code'];
$package = $_GET['package'];

$p_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".$package."'");
$p = mysql_fetch_assoc($p_q);

if($p['receipt_type'] != 2)
{
	$q = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$code."' AND branch_id = '".$_SESSION['login_branchid']."' AND loan_package = '".$package."'");
	$get_user  = mysql_fetch_assoc($q);
	$lc = $get_user['loan_code'];
}else
{
	$month = substr($code, 0, 2);
	$rmonth = date('Y-').$month;
	$q = mysql_query("SELECT * FROM loan_payment_details WHERE receipt_no = '".$code."' AND branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$p['id']."' AND month_receipt = '".$rmonth."'");
	$get_user  = mysql_fetch_assoc($q);
	$lc = $get_user['receipt_no'];
}

if($lc != '')
{
	$err_msg = "Code: ".$lc." is already exists!".$rmonth;
}
else
{
	$err_msg = "";	
}

if (strpos($ic,'-') !== false) {
	$dob = explode('-', $ic);
	$year = substr($dob[0], 0, 2);
	$month = substr($dob[0], 2, 2);
	$date = substr($dob[0], 4, 6);
	
	$yy = substr($dob[0], 0, 1);
	
	if($yy == '0')
	{
		$temp_y = $yy;	
	}else
	{
		$temp_y = $year;	
	}
	
	if($temp_y >= 18 && $temp_y <= 99)
	{
		$real_year = '19'.$year;	
	}else
	{
		$real_year = '20'.$year;	
	}
	
	$real_dob = $real_year.'-'.$month.'-'.$date;
}else
{
	$real_dob = '';		
}

echo $err_msg.'#'.$real_dob;
?>