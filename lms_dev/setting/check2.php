<?php 
//DO NOT LOAD THIS PAGE! (For transfer customers from WELLSTART to BARCELONA only)
/*
include('../include/page_header.php'); 
$get_loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE branch_id = '7' GROUP BY customer_id");
while($get_loan = mysql_fetch_assoc($get_loan_q)){
	$update_cust = mysql_query("UPDATE customer_details SET branch_id = '10', branch_name = 'BARCELONA' WHERE id = '".$get_loan['customer_id']."'") or die(mysql_error());
}
$update_lp = mysql_query("UPDATE customer_loanpackage SET branch_id = '10', branch_name = 'BARCELONA' WHERE branch_id = '7'") or die(mysql_error());
*/
?>