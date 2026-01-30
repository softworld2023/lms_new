<?php
session_start();
include("../include/dbconnection.php");

$q = strtoupper(addslashes($_GET["q"]));
if (!$q) return;

$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE loan_code LIKE '%$q%' AND loan_code NOT LIKE 'MS%' AND  branch_id = '".$_SESSION['login_branchid']."'");

while($rs = mysql_fetch_assoc($sql)) {
	$clientname = $rs['loan_code'];
	echo "$clientname\n";
}
?>