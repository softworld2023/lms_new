<?php
session_start();
require_once("include/dbconnection.php");
require 'include/plugin/PasswordHash.php';

/*$sql = mysql_query("SELECT * FROM loan_scheme");
while($get_q = mysql_fetch_assoc($sql))
{
	$sql2 = mysql_query("SELECT * FROM loan_payment_details WHERE package_id = '".$get_q['id']."'");
	$r = mysql_num_rows($sql2);
	echo $get_q['id']." ".$get_q['scheme']." ".$r;
	echo "<br>";
}
*/
$sql = mysql_query("INSERT INTO balance_rec SET id = '2'");

/*$delete = mysql_query("DELETE FROM loan_scheme WHERE id = '6'");
$delete = mysql_query("DELETE FROM loan_scheme WHERE id = '7'");
$delete = mysql_query("DELETE FROM loan_scheme WHERE id = '8'");
$delete = mysql_query("DELETE FROM loan_scheme WHERE id = '10'");
$delete = mysql_query("DELETE FROM loan_scheme WHERE id = '11'");*/

?>