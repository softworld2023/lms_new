<?php
session_start();
include("../include/dbconnection.php");

$code = $_GET['code'];
$loan_package = $_GET['loan_package'];
$branchid = $_SESSION['login_branchid'];

$p_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".$loan_package."'");
$p = mysql_fetch_assoc($p_q);

/*if($p['receipt_type'] != 2)
{
	//$q = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$code."' AND loan_package = '".$loan_package."'");
	$q = mysql_query("SELECT * FROM loan_payment_details WHERE receipt_no = '".$code."' AND package_id = '".$p['id']."'");
	$get_user  = mysql_fetch_assoc($q);
	$lc = $get_user['receipt_no'];
}else
{
	$month = substr($code, 0, 2);
	$rmonth = date('Y-').$month;
	//$q = mysql_query("SELECT * FROM loan_payment_details WHERE receipt_no = '".$code."' AND branch_id = '".$_SESSION['login_branchid']."' AND package_id = '".$p['id']."'");
	//$q = mysql_query("SELECT * FROM loan_payment_details WHERE receipt_no = '".$code."' AND package_id = '".$p['id']."' AND month_receipt = '".$rmonth."'");
	$q = mysql_query("SELECT * FROM loan_payment_details WHERE receipt_no = '".$code."' AND package_id = '".$p['id']."'");
	$get_user  = mysql_fetch_assoc($q);
	$lc = $get_user['receipt_no'];
*/
$now = new DateTime('now');
$nmonth = $now->format('m');
$nyear = $now->format('Y');

$rmonth = $nyear."-".$nmonth;

//$q = mysql_query("SELECT * FROM loan_payment_details WHERE receipt_no = '".$code."' AND package_id = '".$p['id']."' AND month_receipt = '".$rmonth."' AND branch_id = '".$branchid."'");
$q = mysql_query("SELECT * FROM loan_payment_details WHERE receipt_no = '".$code."' AND month_receipt = '".$rmonth."' AND branch_id = '".$branchid."' AND YEAR(apply_date) = '".$nyear."'");
$get_user  = mysql_fetch_assoc($q);
$lc = $get_user['receipt_no'];

//$q2 = mysql_query("SELECT * FROM loan_payment_details WHERE receipt_no = '".$code."' AND package_id = '".$p['id']."' AND month_receipt = '' AND branch_id = '".$branchid."'");
$q2 = mysql_query("SELECT * FROM loan_payment_details WHERE receipt_no = '".$code."' AND month_receipt = '' AND branch_id = '".$branchid."' AND YEAR(apply_date) = '".$nyear."'");
$get_user2  = mysql_fetch_assoc($q2);
$lc2 = $get_user2['receipt_no'];

$q3 = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$code."' AND branch_id = '".$branchid."' AND YEAR(apply_date) = '".$nyear."'");
$get_user3  = mysql_fetch_assoc($q3);
$lc3 = $get_user3['loan_code'];

//$year = substr($get_user['month_receipt'], 0, -3);
//$month = substr($get_user['month_receipt'], -2);

if($lc != '')
{
	$err_msg = "Code: ".$lc." is already exists! ";
}
elseif($lc2 != '')
{
	$err_msg = "Code: ".$lc2." is already exists! ";
}
elseif($lc3 != '')
{
	$err_msg = "Code: ".$lc3." is already exists! ";
}
else
{
	//$err_msg = "Code: ".$lc." is already exists!".$rmonth;
	//$err_msg = "code: ".$code.", package id: ".$p['id'].", month_receipt: ".$rmonth.", branch_id:".$branchid.". lc: ".$lc.", lc2: ".$lc2;
	$err_msg = "";
}

if (strpos($lc,'-') !== false) {
	$dob = explode('-', $lc);
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