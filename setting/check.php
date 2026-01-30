<?php 
//DO NOT LOAD THIS PAGE! (For transfer customers from JININGO to SETPLAN only)
/*
include('../include/page_header.php'); 
$get_loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE branch_id = '6' GROUP BY customer_id");
while($get_loan = mysql_fetch_assoc($get_loan_q)){
	$update_cust = mysql_query("UPDATE customer_details SET branch_id = '4', branch_name = 'SETPLAN' WHERE id = '".$get_loan['customer_id']."'") or die(mysql_error());
}
$update_lp = mysql_query("UPDATE customer_loanpackage SET branch_id = '4', branch_name = 'SETPLAN' WHERE branch_id = '6'") or die(mysql_error());
*/
?>