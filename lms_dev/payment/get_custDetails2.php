<?php
session_start();
include("../include/dbconnection.php");

//get the q parameter from URL
//$q0 = strtoupper($_GET['q']);

$q = $_GET['q'];


$cust_q = mysql_query("SELECT * FROM customer_details WHERE nric = '".$q."' AND branch_id = '".$_SESSION['login_branchid']."'");
$cust = mysql_fetch_assoc($cust_q);

echo $cust['name']."#".$cust['customercode2']."#".$cust['id'];
?>