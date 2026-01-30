<?php 
include('../include/page_header.php'); 
//Move skim g from MCA to BAYU
/*
$get_loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE branch_id = '1' AND loan_package = 'SKIM G'");
while($get_loan = mysql_fetch_assoc($get_loan_q)){
	$update_cust = mysql_query("UPDATE customer_details SET branch_id = '16', branch_name = 'BAYU' WHERE id = '".$get_loan['customer_id']."'") or die(mysql_error());
}
$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_package = 'SKIM G - MCA', branch_id = '16', branch_name = 'BAYU' WHERE branch_id = '1' AND loan_package = 'SKIM G'") or die(mysql_error());
*/

//Move skim g from 
/*
$get_loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE branch_id = '14' AND loan_package = 'SKIM G'");
while($get_loan = mysql_fetch_assoc($get_loan_q)){
	$update_cust = mysql_query("UPDATE customer_details SET branch_id = '16', branch_name = 'BAYU' WHERE id = '".$get_loan['customer_id']."'") or die(mysql_error());
}
$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_package = 'SKIM G - MAJU KAJANG', branch_id = '16', branch_name = 'BAYU' WHERE branch_id = '14' AND loan_package = 'SKIM G'") or die(mysql_error());
*/
?> 