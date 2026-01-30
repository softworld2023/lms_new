<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if($_POST['action'] == 'update_npd')
{
	$id = $_POST['id'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	
	$update_q = mysql_query("UPDATE loan_payment_details SET next_paymentdate = '".$date."' WHERE id = '".$id."'");
	$update_q1 = mysql_query("UPDATE loan_lejar_details SET next_paymentdate = '".$date."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$msg .= 'Next Payment Date has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}

if($_POST['action'] == 'update_pd')
{
	$id = $_POST['id'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	
	$loan_payment_q = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$loan = mysql_fetch_assoc($loan_payment_q);
	
	$loan_package_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$loan['customer_loanid']."'");
	$package = mysql_fetch_assoc($loan_package_q);
	
	$balance_transaction_q = mysql_query("UPDATE balance_transaction SET date = '".$date."' WHERE customer_loanid = '".$loan['customer_loanid']."' AND date = '".$loan['payment_date']."'") or die (mysql_error());
	
	$balance_rec_q = mysql_query("UPDATE balance_rec SET bal_date = '".$date."' WHERE customer_loanid = '".$loan['customer_loanid']."' AND bal_date = '".$loan['payment_date']."'") or die (mysql_error());
	
	$receipt_q = mysql_query("SELECT * FROM cashbook WHERE type = 'PAY' AND customer_id = '".$package['customer_id']."' AND date = '".$loan['payment_date']."' AND package_id = '".$loan['package_id']."' AND branch_id = '".$loan['branch_id']."'");
	$receipt = mysql_fetch_assoc($receipt_q);
	
	$cashbook_q = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE customer_id = '".$package['customer_id']."' AND date = '".$loan['payment_date']."' AND package_id = '".$loan['package_id']."' AND branch_id = '".$loan['branch_id']."'") or die (mysql_error());
	$cashbook2_q = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE receipt_no = '".$receipt['receipt_no']."' AND date = '".$loan['payment_date']."' AND package_id = '".$loan['package_id']."' AND branch_id = '".$loan['branch_id']."'") or die (mysql_error());
	
	//$cashbook2_q = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE customer_id = '".$package['customer_id']."' AND code = '".$package['loan_code']."' AND date = '".$loan['payment_date']."'") or die (mysql_error());
	
	$update_q = mysql_query("UPDATE loan_payment_details SET payment_date = '".$date."' WHERE id = '".$id."'");
	$update_q1 = mysql_query("UPDATE loan_lejar_details SET payment_date = '".$date."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$msg .= 'Payment Date has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
	
}
if($_POST['action'] == 'update_npd2')
{
	$id = $_POST['id'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	
	$cust = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
	$c = mysql_fetch_assoc($cust);
	
	$payout_q = mysql_query("SELECT * FROM payout_details WHERE customer_loanid = '".$id."'");
	$payout = mysql_fetch_assoc($payout_q);
	
	$commission = $payout['approved_amount'] - $payout['cash_pay'];
	
	$balrec_q1 = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$id."' AND commission = '".$commission."' AND bal_date LIKE '%".$c['payout_date']."%'");
	$balrec1 = mysql_fetch_assoc($balrec_q1);
	
	//update commission date
	$update_com = mysql_query("UPDATE balance_rec SET bal_date = '".$date."' WHERE id = '".$balrec1['id']."'");
	
	
	$balrec_q2 = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$id."' AND bal_date LIKE '%".$c['payout_date']."%' AND loan = '".$c['loan_amount']."'");
	$balrec2 = mysql_fetch_assoc($balrec_q2);
		
	//update balance_rec loan
	$update_b2loan = mysql_query("UPDATE balance_rec SET bal_date = '".$date."' WHERE id = '".$balrec2['id']."'");
		
	$baltrans_q1 = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$id."' AND date LIKE '%".$c['payout_date']."%' AND loan = '".$c['loan_amount']."'");
	$baltrans = mysql_fetch_assoc($baltrans_q1);
			
	$update_bal1 = mysql_query("UPDATE balance_transaction SET date = '".$date."' WHERE id = '".$baltrans['id']."'");		
			
	if($c['loan_type'] == 'Fixed Loan')
	{
		if($c['loan_package'] != 'SKIM CEK')
		{
			$cashbook_q1 = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."' AND date = '".$c['payout_date']."' AND type = 'PAY' AND transaction = 'LOAN' AND amount = '".$c['loan_amount']."'");
			$cashbook1 = mysql_fetch_assoc($cashbook_q1);
			
			//update cashbook loan
			$update_cb1 = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE id = '".$cashbook1['id']."'");
			
			$cashbook_q2 = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."' AND date = '".$c['payout_date']."' AND type = 'COMMISSION' AND transaction = 'KOMISYEN' AND amount = '".$commission."'");
			$cashbook2 = mysql_fetch_assoc($cashbook_q2);
			
			//update cashbook com
			$update_cb2 = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE id = '".$cashbook2['id']."'");
		}else
		{
			$cashbook_q1 = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$payout['id']."' AND date = '".$c['payout_date']."' AND type = 'PAY' AND transaction = 'LOAN' AND amount = '".$c['loan_amount']."'");
			$cashbook1 = mysql_fetch_assoc($cashbook_q1);
			
			//update cashbook loan
			$update_cb1 = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE id = '".$cashbook1['id']."'");
			
			$cashbook_q2 = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."' AND date = '".$c['payout_date']."' AND type = 'COMMISSION' AND transaction = 'KOMISYEN' AND amount = '".$commission."'");
			$cashbook2 = mysql_fetch_assoc($cashbook_q2);
			
			//update cashbook com
			$update_cb2 = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE id = '".$cashbook2['id']."'");
		}
	}else
	{
		$cashbook_q1 = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$payout['id']."' AND date = '".$c['payout_date']."' AND type = 'PAY' AND transaction = 'LOAN' AND amount = '".$c['actual_loanamt']."'");
							//01(15122016) - change  from loan_amount to actual_loanamt
		$cashbook1 = mysql_fetch_assoc($cashbook_q1);
		
		//update cashbook loan
		$update_cb1 = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE id = '".$cashbook1['id']."'");
		
		$cashbook_q2 = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."' AND date = '".$c['payout_date']."' AND type = 'COMMISSION' AND transaction = 'KOMISYEN' AND amount = '".$commission."'");
		$cashbook2 = mysql_fetch_assoc($cashbook_q2);
		
		//update cashbook com
		$update_cb2 = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE id = '".$cashbook2['id']."'");
	}
				
	//update payout_date
	$update_date = mysql_query("UPDATE customer_loanpackage SET payout_date = '".$date."' WHERE id = '".$id."'");
	
	$msg .= 'Payout Date has been successfully updated.<br>';
		
	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	/*
	$cash = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."'");
	$cb = mysql_fetch_assoc($cash);
	
	$balr = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$id."' AND commission != '' AND bal_date LIKE '".$c['payout_date']."'");
	$br = mysql_fetch_assoc($balr);
	
	$pd = mysql_query("SELECT * FROM payout_details WHERE customer_loanid = '".$id."'");
	$payd = mysql_fetch_assoc($pd);
	
	$update_q = mysql_query("UPDATE customer_loanpackage SET payout_date = '".$date."' WHERE id = '".$id."'");
	
	$update_q2 = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE table_id = '".$c['id']."' AND type = 'COMMISSION' AND transaction = 'KOMISYEN' AND date = '".$c['payout_date']."'");
	
	$update_q21 = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE table_id = '".$payd['id']."' AND type = 'PAY' AND transaction = 'LOAN' AND date = '".$c['payout_date']."'");
	
	$update_q22 = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE table_id = '".$c['id']."' AND type = 'PAY' AND transaction = 'LOAN' AND date = '".$c['payout_date']."'");
	
	$update_q3 = mysql_query("UPDATE balance_transaction SET date = '".$date."' WHERE customer_loanid = '".$c['id']."' AND date = '".$c['payout_date']."' AND loan = '".$c['loan_total']."'");
	
	$update_q4 = mysql_query("UPDATE balance_rec SET bal_date = '".$date."' WHERE customer_loanid = '".$c['id']."' AND bal_date = '".$c['payout_date']."' AND loan = '".$c['loan_amount']."'");
	
			
	if($update_q)
	{
		$msg .= 'Payout Date has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}*/
}else
if($_POST['action'] == 'update_aremarks')
{
	$id = $_POST['id'];
	$remarks = addslashes($_POST['remarks']);
	
	//prev remarks 
	$prevremarks_q = mysql_query("SELECT * FROM customer_account WHERE id = '".$id."'");
	$prevremarks = mysql_fetch_assoc($prevremarks_q);
	
	$length = strlen($prevremarks['a_remarks']);
	$length2 = strlen($remarks);
	
	if($length2 > $length)
	{
		$a_remarks1 = substr($remarks, $length);
		$a_remarks = $prevremarks['a_remarks'].$a_remarks1." (".$_SESSION['login_name'].")";
	}else
	{
		$a_remarks1 = $remarks;
		$a_remarks = $prevremarks['a_remarks']."\n".$a_remarks1." (".$_SESSION['login_name'].")";
	}
	
	$update_q = mysql_query("UPDATE customer_account SET a_remarks = '".$a_remarks."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$msg .= 'Customer Notes has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}
?>