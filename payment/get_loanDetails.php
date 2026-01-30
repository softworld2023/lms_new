<?php
session_start();
include("../include/dbconnection.php");

//get the q parameter from URL
//$q0 = strtoupper($_GET['q']);

$q = $_GET['q'];

$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code = '".$q."' AND branch_id = '".$_SESSION['login_branchid']."'");
$loan = mysql_fetch_assoc($loan_q);

$scheme_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".$loan['loan_package']."'");
$scheme = mysql_fetch_assoc($scheme_q);

$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$loan['customer_id']."'");
$cust = mysql_fetch_assoc($cust_q);


if($loan)
{
	echo $scheme['id']."#".$cust['customercode2']."#".$cust['name']."#".$cust['nric']."#".$loan['customer_id'];
}else
{
	echo '';
}
?>