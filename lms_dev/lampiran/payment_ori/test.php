<?php
if($_POST['action'] == 'payloanf2') //flexi tapi resit guna code yang sama
{
	$id = $_POST['id'];
	$nric =$_POST['nric'];
	$amount = $_POST['amount'];
	$int_amount = $_POST['intamount'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	$period = $_POST['period'];
	$month = $_POST['month'];
	$next_paymentdate = date('Y-m-d', strtotime($_POST['nextdate']));
	$next_paymentdate2 = date('Y-m-d', strtotime($_POST['nextdate2']));
	$receipt_no = $_POST['receipt'];
		//$id_q = mysql_query("SELECT * FROM loan_payment_details WHERE nric =  '".$nric."' ORDER BY customer_loanid DESC");
 //$get_id = mysql_fetch_assoc($id_q);
	//$customer_loanid = $get_id['id']+1;
	//$month_receipt = $_POST['monthreceipt'];
	
	$month_receipt = date('Y-m', strtotime($_POST['date']));
	$new_loan = $_POST['newloan'];
	echo $id;
	echo $nric;
	echo $amount;
	echo $int_amount;
	echo $date;
	echo $period;
	echo $month;
	echo $next_paymentdate;
	echo $receipt_no;
}
	
	
	
	
	
?>	