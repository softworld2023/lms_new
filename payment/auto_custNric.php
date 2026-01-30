<?php
session_start();
include("../include/dbconnection.php");

$q = strtoupper(addslashes($_GET["q"]));
if (!$q) return;

$sql = mysql_query("SELECT * FROM customer_details WHERE nric LIKE '%$q%'");

while($rs = mysql_fetch_assoc($sql)) {
	$clientname = $rs['nric'];
	echo "$clientname\n";
}
?>