<?php
session_start();
include("../include/dbconnection.php");

$q = strtoupper(addslashes($_GET["q"]));
if (!$q) return;

$sql = mysql_query("SELECT * FROM enquiry WHERE icno LIKE '%$q%' GROUP BY icno");

while($rs = mysql_fetch_assoc($sql)) {
	$clientname = $rs['icno'];
	echo "$clientname\n";
}
?>