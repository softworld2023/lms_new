<?php
session_start();
include("../include/dbconnection.php");

$q = strtoupper(addslashes($_GET["q"]));
if (!$q) return;

$sql = mysql_query("SELECT * FROM enquiry WHERE cust_name LIKE '%$q%' GROUP BY cust_name");

while($rs = mysql_fetch_assoc($sql)) {
	$clientname = $rs['cust_name'];
	echo "$clientname\n";
}
?>