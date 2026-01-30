<?php
session_start();
include("../include/dbconnection.php");

$q = strtoupper($_GET["q"]);
if (!$q) return;

$sql = mysql_query("SELECT * FROM customer_details WHERE name LIKE '%$q%'");

while($rs = mysql_fetch_assoc($sql)) {
	$clientname = $rs['name'];
	echo "$clientname\n";
}
?>