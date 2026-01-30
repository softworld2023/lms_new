<?php
session_start();
include("../include/dbconnection.php");

$q = strtoupper(addslashes($_GET["q"]));
if (!$q) return;

$sql = mysql_query("SELECT * FROM customer_details WHERE customercode2 LIKE '%$q%'");

while($rs = mysql_fetch_assoc($sql)) {
	$clientname = $rs['customercode2'];
	echo "$clientname\n";
}
?>