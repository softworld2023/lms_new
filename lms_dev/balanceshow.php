<?php
session_start();
require_once("include/dbconnection.php");
require 'include/plugin/PasswordHash.php';

$sql = mysql_query("SELECT * FROM balance_rec WHERE package_id = '12'");
while($get_q = mysql_fetch_assoc($sql))
{
	echo $get_q['id']." ".$get_q['package_id']." ".$get_q['customer_loanid']." ".$get_q['loan']." ".$get_q['received']." ".$get_q['commission']." ".$get_q['interest']." ".$get_q['interest2'];
	echo "<br>";
}

//update
//$update = mysql_query("UPDATE balance_rec SET received = '0', interest2 = '33.60' WHERE id = '6'");
/*$delete = mysql_query("DELETE FROM loan_scheme WHERE id = '6'");
$delete = mysql_query("DELETE FROM loan_scheme WHERE id = '7'");
$delete = mysql_query("DELETE FROM loan_scheme WHERE id = '8'");
$delete = mysql_query("DELETE FROM loan_scheme WHERE id = '10'");
$delete = mysql_query("DELETE FROM loan_scheme WHERE id = '11'");*/

?>