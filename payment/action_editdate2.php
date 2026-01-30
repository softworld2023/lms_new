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
	
	if($update_q)
	{
		$msg .= 'Next Payment Date has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}
if($_POST['action'] == 'update_npd2')
{
	$id = $_POST['id'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	
	
	$cust = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
	$c = mysql_fetch_assoc($cust);
	
	$cash = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."'");
	$cb = mysql_fetch_assoc($cash);
	
	$balr = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$id."' AND commission != '' AND bal_date LIKE '".$c['payout_date']."'");
	$br = mysql_fetch_assoc($balr);
	
	$pd = mysql_query("SELECT * FROM payout_details WHERE id = '".$id."'");
	$payd = mysql_fetch_assoc($pd);
	
	$update_q = mysql_query("UPDATE customer_loanpackage SET payout_date = '".$date."' WHERE id = '".$id."'");
	
	
	
	if($update_q)
	{
	
	
	
	$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$c['id']."' AND loan = '".$c['loan_total']."' AND date LIKE '%".$c['payout_date']."%' ORDER BY id ASC LIMIT 1");
		$bal1 = mysql_fetch_assoc($bal1_q);
		 
		$update_b1 = mysql_query("UPDATE balance_transaction SET date = '".$date."' WHERE id = '".$bal1['id']."'");
	
		$cb_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$c['id']."' AND type = 'PAY' AND amount = '".$c['loan_amount']."' AND transaction = 'LOAN' AND date LIKE '%".$c['payout_date']."%'");
	
	//mysql_query("SELECT * FROM cashbook WHERE table_id = '".$c['id']."' AND type = 'PAY' AND amount = '".$c['loan_amount']."' AND transaction = 'LOAN' AND date LIKE '%".$c['payout_date']."%' AND branch_id = '".$br['branch_id']."' AND branch_name = '".$br['branch_name']."'");
		$cbook = mysql_fetch_assoc($cb_q);
		$cbookid = $cbook['id'];
	
		$update_c = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE id = '".$cbook['id']."' ");
	
	$cb_q2 = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$c['id']."' AND type = 'COMMISSION' AND amount = '".$br['commission']."' AND transaction = 'KOMISYEN' AND date LIKE '%".$c['payout_date']."%' AND branch_id = '".$br['branch_id']."' AND branch_name = '".$br['branch_name']."'");
		$cbook2 = mysql_fetch_assoc($cb_q2);
		
		$update_c2 = mysql_query("UPDATE cashbook SET date = '".$date."' WHERE id = '".$cbook2['id']."' ");
	
			//select from bal2
	$bal_rec = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$c['id']."' AND loan = '".$c['loan_amount']."' AND branch_id = '".$cb['branch_id']."' AND branch_name = '".$cb['branch_name']."' AND bal_date LIKE '%".$c['payout_date']."%'  ORDER BY id DESC LIMIT 1");
		$balr = mysql_fetch_assoc($bal_rec);
			
		$update_br = mysql_query("UPDATE balance_rec SET bal_date = '".$date."' WHERE customer_loanid = '".$id."' AND id = '".$balr['id']."'");
		$update_br2 = mysql_query("UPDATE balance_rec SET bal_date = '".$date."' WHERE customer_loanid = '".$id."' AND id = '".$br['id']."'");
	}
			
	/*if($update_q)
	{
			//select from bal2
		$bal_rec2 = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$cb['table_id']."' AND commission = '".$cb['amount']."' AND branch_id = '".$cb['branch_id']."' AND branch_name = '".$cb['branch_name']."' AND bal_date LIKE '%".$cb['date']."%' ORDER BY id DESC LIMIT 1");
		$balr2 = mysql_fetch_assoc($bal_rec2);
			
		$update_br2 = mysql_query("UPDATE balance_rec SET bal_date = '".$date."' WHERE customer_loanid = '".$customer_loanid."'");
		}*/

	if($update_q)
	{
		$msg .= $cbookid;
		$msg .= 'Payout Date has been successfully updated.-<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
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