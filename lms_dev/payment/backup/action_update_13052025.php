<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");

if($_POST['action'] == 'update_intrate')
{
	$id = $_POST['id'];
	$intrate = $_POST['intrate'];
	
	//update interest rate
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_interest = '".$intrate."' WHERE id = '".$id."'");
	$update_q2 = mysql_query("UPDATE loan_payment_details SET int_percent = '".$intrate."' WHERE customer_loanid = '".$id."'");

	//select loan details
	$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
	$loan = mysql_fetch_assoc($loan_q);
	
	//calculate loan total
	/*$loantotal1 = ((($loan['loan_interest'] / 100) * $loan['loan_amount']) * $loan['loan_period']);
	$monthly1 = $loantotal1 / $loan['loan_period'];
	$monthly = ceil($monthly1);
	$loantotal = $monthly * $loan['loan_period'];
	$loaninttotal = $loantotal - $loan['loan_amount'];*/
	
	//update loan_package
	//$lp_q = mysql_query("UPDATE customer_loanpackage SET loan_interesttotal = '".$loaninttotal."', loan_total = '".$loantotal."' WHERE id = '".$id."'");
	
	
		
	
	if($update_q)
	{
		$msg .= 'Loan interest rate has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'update_intrateFixed')
{
	$id = $_POST['id'];
	$intrate = $_POST['intrate'];
	
	//update interest rate
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_interest = '".$intrate."' WHERE id = '".$id."'");
	$update_q2 = mysql_query("UPDATE loan_payment_details SET int_percent = '".$intrate."' WHERE customer_loanid = '".$id."'");
	
	
	//select loan details
	$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
	$loan = mysql_fetch_assoc($loan_q);
	
	//calculate loan total
	$loantotal1 = ((($loan['loan_interest'] / 100) * $loan['loan_amount']) * $loan['loan_period']) + $loan['loan_amount'];
	$monthly1 = $loantotal1 / $loan['loan_period'];
	$monthly = ceil($monthly1);
	$loantotal = $monthly * $loan['loan_period'];
	$loaninttotal = $loantotal - $loan['loan_amount'];
	
	//update loan_package
	$lp_q = mysql_query("UPDATE customer_loanpackage SET loan_interesttotal = '".$loaninttotal."', loan_total = '".$loantotal."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$msg .= 'Loan interest rate has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'update_NewReceipt')
{
	$id = $_POST['id'];
	$new_receipt = $_POST['new_receipt'];
	
	$loan = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$lpd = mysql_fetch_assoc($loan);
	
	$clp_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$lpd['customer_loanid']."'");
	$clp = mysql_fetch_assoc($clp_q);
	
	if($clp['loan_code'] == $lpd['receipt_no'])
	{
		$update_q3 = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$new_receipt."' WHERE id = '".$lpd['customer_loanid']."'");
	}
	
	//update new receipt
	$update_q = mysql_query("UPDATE loan_payment_details SET receipt_no = '".$new_receipt."' WHERE id = '".$id."'");

	$update_q2 = mysql_query("UPDATE cashbook SET receipt_no = '".$new_receipt."' WHERE table_id = '".$id."'");
	

	if($update_q)
	{
		$msg .= 'New Receipt has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}
else
if($_POST['action'] == 'update_NewReceiptMonth')
{
	$id = $_POST['id'];
	$month_receipt = $_POST['month_receipt'];
	
	$loan = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$lpd = mysql_fetch_assoc($loan);
	
	$br_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$lpd['customer_loanid']."'");
	$br = mysql_fetch_assoc($br_q);
	
	//update new receipt
	
	$update_q = mysql_query("UPDATE loan_payment_details SET month_receipt = '".$month_receipt."' WHERE id = '".$id."'");

	$update_q2 = mysql_query("UPDATE balance_rec SET month_receipt = '".$month_receipt."' WHERE customer_loanid = '".$lpd['customer_loanid']."' AND month_receipt = '".$lpd['month_receipt']."'");
	

	if($update_q)
	{
		$msg .= 'New Receipt Month has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'update_period')
{
	$id = $_POST['id'];
	$period = $_POST['period'];
	
	//update interest rate
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_period = '".$period."' WHERE id = '".$id."'");
	

	if($update_q)
	{
		$msg .= 'Loan period has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'update_balance')
{
	$id = $_POST['id'];
	$newbalance = str_replace(',','',$_POST['newbalance']);
	
	
	$update_q = mysql_query("UPDATE loan_payment_details SET balance = '".$newbalance."' WHERE customer_loanid = '".$id."' AND payment = '0' AND payment_date = '0000-00-00'");
	
	if($update_q)
	{
		$msg .= 'Loan latest balance has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'update_pokok')
{
	$id = $_POST['id'];
	$loan_pokok = $_POST['loan_pokok'];
	
	//update pokok
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_pokok = '".$loan_pokok."' WHERE id = '".$id."'");
	

	if($update_q)
	{
		$msg .= 'Loan pokok has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'update_settlement')
{
	$id = $_POST['id'];
	$yes = $_POST['yes'];
	$loan_settlement = $_POST['loan_settlement'];
	$amount = str_replace(',','',$_POST['amount']);


	$update_q = mysql_query("UPDATE customer_loanpackage SET settlement = '".$yes."' WHERE id = '".$id."'");

	


	$select_q1 = mysql_query("SELECT * FROM loan_settlement WHERE customer_id ='".$id."'");
	if(mysql_num_rows($select_q1) == 0){

	$update_q1 = mysql_query("INSERT INTO loan_settlement SET customer_id ='".$id."', settlement_period = '".$loan_settlement."',settlement_amount = '".$amount."' ");
	}else{
	$update_q1 = mysql_query("UPDATE loan_settlement SET settlement_period = '".$loan_settlement."' ,settlement_amount = '".$amount."' WHERE customer_id ='".$id."'");
	}
	

	if($update_q)
	{
		$msg .= 'Loan settlement has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'update_total_loan')
{
	$id = $_POST['id'];
	$loan_total = str_replace(',','',$_POST['loan_total']);
	
	//update interest rate
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$id."'");
	

	if($update_q)
	{
		$msg .= 'Loan total has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'update_aremarks')
{
	$id = $_POST['id'];
	$a_remarks = addslashes($_POST['remarks']);
	
	$update_q = mysql_query("UPDATE customer_account SET a_remarks = '".$a_remarks."', update_byid = '".$_SESSION['taplogin_id']."', update_byname = '".$_SESSION['login_name']."', update_date = now() WHERE id = '".$id."'");
	
	if($update_q)
	{
		$msg .= 'Loan notes has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'update_status')
{
	$id = $_POST['id'];
	$status = addslashes($_POST['status']);
	
	$update_q = mysql_query("UPDATE late_interest_record SET status = '".$status."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$msg .= 'Late interest has been successfully changed to CLEARED.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}
else
if($_POST['action'] == 'update_mpr_status')
{
	$mprid = $_POST['mprid'];
	$status = addslashes($_POST['status']);
	
	$update_q = mysql_query("UPDATE monthly_payment_record SET status = '".$status."' WHERE id = '".$mprid."'");
	
	if($update_q)
	{
		$msg .= 'Monthly Payment has been successfully changed to CLEARED.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'insert_resit')
{
	$id = $_POST['id'];
	$resit = $_POST['resit'];
	
	$update_q = mysql_query("UPDATE loan_payment_details SET no_resit = '".$resit."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$msg .= 'Receipt No has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'insert_month_receipt')
{
	$id = $_POST['id'];
	$month_receipt = $_POST['month_receipt'];
	
	$update_q = mysql_query("UPDATE loan_payment_details SET month_receipt = '".$month_receipt."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$msg .= 'Payment Month has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if($_POST['action'] == 'delete_payfixed')//delete for payloan_a.php
{
	$id = $_POST['id'];
	$loanid = $_POST['loanid'];
	
	//select payment record
	$pr_q = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$pr = mysql_fetch_assoc($pr_q);
	
	//delete from cashbook
	$delete_cb = mysql_query("DELETE FROM cashbook WHERE package_id = '".$pr['package_id']."' AND type = 'RECEIVED' AND table_id = '".$id."' AND code = '".$pr['receipt_no']."' AND amount = '".$pr['payment']."' AND date LIKE '%".$pr['payment_date']."%'");
	
	if($delete_cb)
	{
		//select from $bal1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr['package_id']."' AND received = '".$pr['payment']."' AND date LIKE '%".$pr['payment_date']."%' ORDER BY id DESC LIMIT 1");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		//delete from bal1 
		$delete_b1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
		
		if($delete_b1)
		{
			//select from bal2
			$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr['package_id']."' AND received = '".$pr['payment']."' AND bal_date LIKE '%".$pr['payment_date']."%' ORDER BY id DESC LIMIT 1");
			$bal2 = mysql_fetch_assoc($bal2_q);
			
			//delete from bal2
			$delete_b2 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
			
			
			if($delete_b2)
			{
				//update payment_rec
				$update_q = mysql_query("UPDATE loan_payment_details SET month_receipt = '', payment = '', payment_date = '',loan_status = '' WHERE id = '".$id."'");
			
				
				//delete from loan_payment_details	(next payment)
	$delete_payrec = mysql_query("DELETE FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$loanid."'");

				$update_package = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Paid' WHERE loan_code = '".$pr['receipt_no']."' ");

	
			$late_q = mysql_query("SELECT * FROM late_interest_record WHERE loan_code = '".$pr['receipt_no']."' ");
			$late = mysql_fetch_assoc($late_q);
			
			//delete from bal2
			$late_q1 = mysql_query("DELETE FROM late_interest_record WHERE id = '".$late['id']."'");

			$delete_bd = mysql_query("DELETE FROM baddebt_record WHERE loan_code = '".$pr['receipt_no']."' ");

			
				if($update_q)
				{
					$msg .= 'Payment has been successfully delete from the record.<br>';
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				}
			}
		}
	}
}else
if($_POST['action'] == 'delete_payfixed2')//delete for payloan_f.php
{
	$id = $_POST['id'];
	$loanid = $_POST['loanid'];
	$amount = $_POST['amount'];
	//$date = $_POST['date'];
	
	//select payment record
	$pr_q = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$pr2 = mysql_fetch_assoc($pr_q);
	
	//delete from cashbook (received ,ccm)
	$delete_cb2 = mysql_query("DELETE FROM cashbook WHERE package_id = '".$pr2['package_id']."' AND type = 'RECEIVED' AND table_id = '".$id."' AND code = '".$pr2['receipt_no']."' AND amount = '".$pr2['payment']."' AND date LIKE '%".$pr2['payment_date']."%' AND transaction = 'CCM'");
	
	//delete from cashbook (commission, komisyen2)
	$delete_cb3 = mysql_query("DELETE FROM cashbook WHERE package_id = '".$pr2['package_id']."' AND type = 'COMMISSION' AND table_id = '0' AND receipt_no = '".$pr2['receipt_no']."' AND amount = '".$pr2['payment_int']."' AND date LIKE '%".$pr2['payment_date']."%' AND transaction = 'KOMISYEN2'");
	
	//$new_loan = 0 (Update total loan after delete)
	if($new_loan == '' || $new_loan == 0)
	{
		$updateloanbal = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$pr2['balance']."' WHERE id = '".$pr2['customer_loanid']."'");
	}
	
//delete from balance transaction
	if($delete_cb2)
	{
		//select from $bal1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr2['package_id']."' AND received = '".$pr2['payment']."' AND date LIKE '%".$pr2['payment_date']."%' ORDER BY id DESC LIMIT 1");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		//delete from bal1 
		$delete_b1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
		
		if($delete_cb3)
	{
		//select from $bal1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr2['package_id']."' AND received = '".$pr2['payment']."' AND date LIKE '%".$pr2['payment_date']."%' ORDER BY id DESC LIMIT 1");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		//delete from bal1 
		$delete_b3 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
	}

//delete from balance rec
		if($delete_b1)
		{
			//select from bal2
			$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr2['package_id']."' AND received = '".$pr2['payment']."' AND bal_date LIKE '%".$pr2['payment_date']."%' ORDER BY id DESC LIMIT 1");
			$bal2 = mysql_fetch_assoc($bal2_q);
			
			//delete from bal2
			$delete_b2 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		}
		
			if($delete_b3)
		{
			//select from bal2
			$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr2['package_id']."' AND interest2 = '".$pr2['payment_int']."' AND bal_date LIKE '%".$pr2['payment_date']."%' ORDER BY id DESC LIMIT 1");
			$bal2 = mysql_fetch_assoc($bal2_q);
			
			//delete from bal2
			$delete_b4 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
			
			
			if($delete_b2)
			{
				//update payment_rec
				$update_q = mysql_query("UPDATE loan_payment_details SET payment = '', payment_date = '', payment_int = '' WHERE id = '".$id."'");
			
			}
				if($delete_b4)
			{
				//update payment_rec
				$update_q2 = mysql_query("UPDATE loan_payment_details SET payment = '', payment_date = '' WHERE id = '".$id."'");
			
				
				
				//delete from loan_payment_details	(next payment)
	$delete_payrec2 = mysql_query("DELETE FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$loanid."'");

			
				if($update_q)
				{
					$msg .= 'Payment has been successfully delete from the record.<br>';
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				
				}
			}
		}
	}
}else
if($_POST['action'] == 'delete_payfixed3')//delete for payloan.php
{
	$id = $_POST['id'];
	$loanid = $_POST['loanid'];
	$package_id = $_POST['package_id'];

	$lpd_q = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");	
	$lpd = mysql_fetch_assoc($lpd_q);
	
	//next record
	$lpd2_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$loanid."' AND id > '".$id."'");	
	$lpd2 = mysql_fetch_assoc($lpd2_q);
	
	
	
	if($lpd['payment'] != 0 && $lpd['payment_int'] == 0 && $lpd['month_receipt'] == $lpd2['month_receipt'] && $lpd2['balance'] != 0)
	{
		//delete next record 
		$delete_nrcd = mysql_query("DELETE FROM loan_payment_details WHERE id = '".$lpd2['id']."'");

		
		//delete cashbook
		$cb1_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."' AND type = 'RECEIVED' AND amount = '".$lpd['payment']."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' ORDER BY id DESC");
		$cb1 = mysql_fetch_assoc($cb1_q);
		
		$delete_cb1 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb1['id']."'");
		
		
		$totalbalance = $lpd['balance'] + $lpd2['balance'] - $lpd['payment'];
		$comamt = ($lpd['int_percent']/100) * $totalbalance;
		
		//delete if got new loan
		$cb2_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '0' AND type = 'PAY' AND transaction = 'LOAN' AND amount = '".$totalbalance."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd2['receipt_no']."' ORDER BY id DESC");
		$cb2 = mysql_fetch_assoc($cb2_q);
		
		$delete_cb2 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb2['id']."'");
		
		$cb3_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '0' AND type = 'COMMISSION' AND transaction = 'KOMISYEN' AND code = '' AND amount = '".$comamt."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd2['receipt_no']."' ORDER BY id DESC");
		$cb3 = mysql_fetch_assoc($cb3_q);
		
		$delete_cb3 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb3['id']."'");
		
		//delete balance2
		$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND received = '".$lpd['payment']."' AND bal_date LIKE '%".$lpd['payment_date']."%' AND month_receipt = '".$lpd['month_receipt']."' ORDER BY id DESC");
		$bal2 = mysql_fetch_assoc($bal2_q);
		
		$delete_bal2 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		
		$bal2_q1 = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND loan = '".$totalbalance."' AND bal_date LIKE '%".$lpd['payment_date']."%' AND month_receipt = '".$lpd2['month_receipt']."' ORDER BY id DESC");
		$bal21 = mysql_fetch_assoc($bal2_q1);
		
		$delete_bal21 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal21['id']."'");
		
		//delete from balance1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND received = '".$lpd['payment']."' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		$delete_bal1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
		
		$bal1_q1 = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND loan = '".$totalbalance."' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal11 = mysql_fetch_assoc($bal1_q1);
		
		$delete_bal11 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal11['id']."'");
		
		//update customer_loanpackage & loan paymanet_details
		$update_clp = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$lpd['receipt_no']."', loan_amount = '".$lpd['balance']."', loan_total = '".$lpd['balance']."' WHERE id = '".$loanid."'");
		$update_lpd = mysql_query("UPDATE loan_payment_details SET payment = '', payment_date = '' WHERE id = '".$id."'");
	}
	
	if($lpd['payment'] != 0 && $lpd['payment_int'] != 0 && $lpd['month_receipt'] == $lpd2['month_receipt'] && $lpd2['balance'] != 0)
	{
		//delete next record 
		$delete_nrcd = mysql_query("DELETE FROM loan_payment_details WHERE id = '".$lpd2['id']."'");
		
		//delete cashbook
		$cb1_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."' AND type = 'RECEIVED' AND amount = '".$lpd['payment']."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' ORDER BY id DESC");
		$cb1 = mysql_fetch_assoc($cb1_q);
		$delete_cb1 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb1['id']."'");
		
		//delete int from cashbook
		$cb2_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '0' AND type = 'COMMISSION' AND amount = '".$lpd['payment_int']."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd['receipt_no']."' ORDER BY id DESC");
		$cb2 = mysql_fetch_assoc($cb2_q);
		
		$delete_cb2 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb2['id']."'");
		
		//delete balance2
		$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND received = '".$lpd['payment']."' AND bal_date LIKE '%".$lpd['payment_date']."%' AND month_receipt = '".$lpd['month_receipt']."' ORDER BY id DESC");
		$bal2 = mysql_fetch_assoc($bal2_q);
		
		$delete_bal2 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		
		//delete from balance1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND received = '".$lpd['payment']."' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		$delete_bal1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
		
		//update customer_loanpackage & loan paymanet_details
		$update_clp = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$lpd['receipt_no']."', loan_amount = '".$lpd['balance']."', loan_total = '".$lpd['balance']."' WHERE id = '".$loanid."'");
		$update_lpd = mysql_query("UPDATE loan_payment_details SET payment = '', payment_int = '', payment_date = '' WHERE id = '".$id."'");

	}
	
	//extra
	if($lpd['payment'] == 0 && $lpd['payment_int'] == 0 && $lpd['month_receipt'] == $lpd2['month_receipt'] && $lpd['balance'] < $lpd2['balance'] && $lpd2['balance'] != 0)
	{
		//delete next record 
		$delete_nrcd = mysql_query("DELETE FROM loan_payment_details WHERE id = '".$lpd2['id']."'");

		
		$extamt = $lpd2['balance'] - $lpd['balance'];
		$comamt = ($lpd['int_percent']/100) * $extamt;
		//delete cashbook
		$cb1_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '0' AND type = 'PAY' AND transaction = 'EXT' AND amount = '".$extamt."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd['receipt_no']."' ORDER BY id DESC");
		$cb1 = mysql_fetch_assoc($cb1_q);
		$delete_cb1 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb1['id']."'");
		
		//delete int from cashbook
		$cb2_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '0' AND type = 'COMMISSION' AND amount = '".$comamt."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd['receipt_no']."' ORDER BY id DESC");
		$cb2 = mysql_fetch_assoc($cb2_q);
		
		$delete_cb2 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb2['id']."'");
		
		//delete balance2
		$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND loan = '".$extamt."' AND bal_date LIKE '%".$lpd['payment_date']."%' AND month_receipt = '".$lpd['month_receipt']."' ORDER BY id DESC");
		$bal2 = mysql_fetch_assoc($bal2_q);
		
		$delete_bal2 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		
		//delete from balance1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND loan = '".$extamt."' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		$delete_bal1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
		
		$bal1_q2 = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND loan = '' AND received = '0' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal12 = mysql_fetch_assoc($bal1_q2);
		
		$delete_bal12 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal12['id']."'");
		
		//update customer_loanpackage & loan paymanet_details
		$update_clp = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$lpd['receipt_no']."', loan_amount = '".$lpd['balance']."', loan_total = '".$lpd['balance']."' WHERE id = '".$loanid."'");
		$update_lpd = mysql_query("UPDATE loan_payment_details SET payment = '', payment_int = '', payment_date = '' WHERE id = '".$id."'");

	}
	
	//extra with interest != 0
	if($lpd['payment'] == 0 && $lpd['payment_int'] != 0 && $lpd['month_receipt'] == $lpd2['month_receipt'] && $lpd['balance'] < $lpd2['balance'] && $lpd2['balance'] != 0)
	{
		//delete next record 
		$delete_nrcd = mysql_query("DELETE FROM loan_payment_details WHERE id = '".$lpd2['id']."'");
		
		$extamt = $lpd2['balance'] - $lpd['balance'];
		$comamt = $lpd['payment_int'];
		//delete cashbook
		$cb1_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '0' AND type = 'PAY' AND transaction = 'EXT' AND amount = '".$extamt."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd['receipt_no']."' ORDER BY id DESC");
		$cb1 = mysql_fetch_assoc($cb1_q);
		$delete_cb1 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb1['id']."'");
		
		//delete int from cashbook
		$cb2_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '0' AND type = 'COMMISSION' AND amount = '".$comamt."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd['receipt_no']."' ORDER BY id DESC");
		$cb2 = mysql_fetch_assoc($cb2_q);
		
		$delete_cb2 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb2['id']."'");
		
		//delete balance2
		$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND loan = '".$extamt."' AND bal_date LIKE '%".$lpd['payment_date']."%' AND month_receipt = '".$lpd['month_receipt']."' ORDER BY id DESC");
		$bal2 = mysql_fetch_assoc($bal2_q);
		
		$delete_bal2 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		
		//delete from balance1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND loan = '".$extamt."' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		$delete_bal1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
		
		$bal1_q2 = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND loan = '' AND received = '0' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal12 = mysql_fetch_assoc($bal1_q2);
		
		$delete_bal12 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal12['id']."'");
		
		//update customer_loanpackage & loan paymanet_details
		$update_clp = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$lpd['receipt_no']."', loan_amount = '".$lpd['balance']."', loan_total = '".$lpd['balance']."' WHERE id = '".$loanid."'");
		$update_lpd = mysql_query("UPDATE loan_payment_details SET payment = '', payment_int = '', payment_date = '' WHERE id = '".$id."'");

	}
	
	//extra (new receipt month but 0 payment)
	if($lpd['payment'] == 0 && $lpd['payment_int'] == 0 && $lpd['month_receipt'] != $lpd2['month_receipt'] && $lpd['balance'] < $lpd2['balance'] && $lpd2['balance'] != 0)
	{
		//delete next record 
		$delete_nrcd = mysql_query("DELETE FROM loan_payment_details WHERE id = '".$lpd2['id']."'");

		
		$extamt = $lpd2['balance'] - $lpd['balance'];
		$comamt = ($lpd['int_percent']/100) * $extamt;
		//delete cashbook
		$cb1_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '0' AND type = 'PAY' AND transaction = 'EXT' AND amount = '".$extamt."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd2['receipt_no']."' ORDER BY id DESC");
		$cb1 = mysql_fetch_assoc($cb1_q);
		$delete_cb1 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb1['id']."'");
		
		//delete int from cashbook
		$cb2_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '0' AND type = 'COMMISSION' AND amount = '".$comamt."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd2['receipt_no']."' ORDER BY id DESC");
		$cb2 = mysql_fetch_assoc($cb2_q);
		
		$delete_cb2 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb2['id']."'");
		
		//delete balance2
		$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND loan = '".$extamt."' AND bal_date LIKE '%".$lpd['payment_date']."%' AND month_receipt = '".$lpd2['month_receipt']."' ORDER BY id DESC");
		$bal2 = mysql_fetch_assoc($bal2_q);
		
		$delete_bal2 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		
		//delete from balance1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND loan = '".$extamt."' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		$delete_bal1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
		
		$bal1_q2 = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND loan = '' AND received = '0' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal12 = mysql_fetch_assoc($bal1_q2);
		
		$delete_bal12 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal12['id']."'");
		
		//update customer_loanpackage & loan paymanet_details
		$update_clp = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$lpd['receipt_no']."', loan_amount = '".$lpd['balance']."', loan_total = '".$lpd['balance']."' WHERE id = '".$loanid."'");
		$update_lpd = mysql_query("UPDATE loan_payment_details SET payment = '', payment_int = '', payment_date = '' WHERE id = '".$id."'");

	}
	
	
	//new receipt but forgot to key in new loan
	if($lpd['payment'] != 0 && $lpd['payment_int'] == 0 && $lpd2['balance'] == 0)
	{
		//delete next record 
		$delete_nrcd = mysql_query("DELETE FROM loan_payment_details WHERE id = '".$lpd2['id']."'");

		
		//delete cashbook
		$cb1_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."' AND type = 'RECEIVED' AND transaction = 'REC' AND amount = '".$lpd['payment']."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd['receipt_no']."' ORDER BY id DESC");
		$cb1 = mysql_fetch_assoc($cb1_q);
		$delete_cb1 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb1['id']."'");
		
		//delete balance2
		$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND received = '".$lpd['payment']."' AND bal_date LIKE '%".$lpd['payment_date']."%' AND month_receipt = '".$lpd['month_receipt']."' ORDER BY id DESC");
		$bal2 = mysql_fetch_assoc($bal2_q);
		
		$delete_bal2 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		
		//delete from balance1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND received = '".$lpd['payment']."' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		$delete_bal1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
		
		//update customer_loanpackage & loan paymanet_details
		$update_clp = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$lpd['receipt_no']."', loan_amount = '".$lpd['balance']."', loan_total = '".$lpd['balance']."', loan_status = 'Paid' WHERE id = '".$loanid."'");
		$update_lpd = mysql_query("UPDATE loan_payment_details SET payment = '', payment_int = '', payment_date = '' WHERE id = '".$id."'");

	}
	
	//new receipt & got int but forgot to key in new loan
	if($lpd['payment'] != 0 && $lpd['payment_int'] != 0 && $lpd2['balance'] == 0)
	{
		//delete next record 
		$delete_nrcd = mysql_query("DELETE FROM loan_payment_details WHERE id = '".$lpd2['id']."'");

		
		//delete cashbook
		$cb1_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."' AND type = 'RECEIVED' AND transaction = 'REC' AND amount = '".$lpd['payment']."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd['receipt_no']."' ORDER BY id DESC");
		$cb1 = mysql_fetch_assoc($cb1_q);
		$delete_cb1 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb1['id']."'");
		
		//delete balance2
		$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND received = '".$lpd['payment']."' AND bal_date LIKE '%".$lpd['payment_date']."%' AND month_receipt = '".$lpd['month_receipt']."' ORDER BY id DESC");
		$bal2 = mysql_fetch_assoc($bal2_q);
		
		$delete_bal2 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		
		//delete from balance1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND received = '".$lpd['payment']."' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		$delete_bal1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
		
		//update customer_loanpackage & loan paymanet_details
		$update_clp = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$lpd['receipt_no']."', loan_amount = '".$lpd['balance']."', loan_total = '".$lpd['balance']."', loan_status = 'Paid' WHERE id = '".$loanid."'");
		$update_lpd = mysql_query("UPDATE loan_payment_details SET payment = '', payment_int = '', payment_date = '' WHERE id = '".$id."'");

	}
	
	//new receipt normal case
	if($lpd['payment'] != 0 && $lpd['payment'] == $lpd['balance'] && $lpd['payment_int'] == 0 && $lpd2['balance'] != 0)
	{
		//delete next record 
		$delete_nrcd = mysql_query("DELETE FROM loan_payment_details WHERE id = '".$lpd2['id']."'");

		
		//delete cashbook
		$cb1_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."' AND type = 'RECEIVED' AND transaction = 'REC' AND amount = '".$lpd['payment']."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd['receipt_no']."' ORDER BY id DESC");
		$cb1 = mysql_fetch_assoc($cb1_q);
		$delete_cb1 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb1['id']."'");
		
		$extamt = $lpd2['balance'];
		$comamt = ($lpd['int_percent']/100) * $extamt;
		//delete cashbook
		$cb2_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '0' AND type = 'PAY' AND transaction = 'LOAN' AND amount = '".$extamt."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd2['receipt_no']."' ORDER BY id DESC");
		$cb2 = mysql_fetch_assoc($cb2_q);
		$delete_cb2 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb2['id']."'");
		
		//delete int from cashbook
		$cb3_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '0' AND type = 'COMMISSION' AND amount = '".$comamt."' AND date = '".$lpd['payment_date']."' AND branch_id = '".$lpd['branch_id']."' AND receipt_no = '".$lpd2['receipt_no']."' ORDER BY id DESC");
		$cb3 = mysql_fetch_assoc($cb3_q);
		
		$delete_cb3 = mysql_query("DELETE FROM cashbook WHERE id = '".$cb3['id']."'");
		
		//delete balance2
		$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND received = '".$lpd['payment']."' AND bal_date LIKE '%".$lpd['payment_date']."%' AND month_receipt = '".$lpd['month_receipt']."' ORDER BY id DESC");
		$bal2 = mysql_fetch_assoc($bal2_q);
		
		$delete_bal2 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		
		//delete balance2 loan
		$bal21_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND loan = '".$extamt."' AND bal_date LIKE '%".$lpd['payment_date']."%' AND month_receipt = '".$lpd2['month_receipt']."' ORDER BY id DESC");
		$bal21 = mysql_fetch_assoc($bal21_q);
		
		$delete_bal21 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal21['id']."'");
		
		
		//delete from balance1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND received = '".$lpd['payment']."' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		$delete_bal1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
		
		//delete from balance1 loan
		$bal11_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND loan = '".$extamt."' AND date LIKE '%".$lpd['payment_date']."%' ORDER BY id DESC");
		$bal11 = mysql_fetch_assoc($bal11_q);
		
		$delete_bal11 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal11['id']."'");
		
		//update customer_loanpackage & loan paymanet_details
		$update_clp = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$lpd['receipt_no']."', loan_amount = '".$lpd['balance']."', loan_total = '".$lpd['balance']."', loan_status = 'Paid' WHERE id = '".$loanid."'");
		$update_lpd = mysql_query("UPDATE loan_payment_details SET payment = '', payment_int = '', payment_date = '' WHERE id = '".$id."'");

	}
	
	$msg .= 'Payment has been successfully delete from the record.<br>';

	$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	/*
	//select payment record
	$pr_q = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$pr3 = mysql_fetch_assoc($pr_q);
	
	$pr2_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$pr3['customer_loanid']."' AND id > '".$id."'");
	$pr22 = mysql_fetch_assoc($pr2_q);
	
	//calculate amount for interest
	$inttt = ($pr22['int_percent']/100);
	$inter = ($pr22['balance'] * $inttt);
	
	//delete from cashbook
	$delete_cba = mysql_query("DELETE FROM cashbook WHERE package_id = '".$pr3['package_id']."' AND type = 'RECEIVED' AND table_id = '".$id."' AND code = '".$pr3['receipt_no']."' AND date LIKE '%".$pr3['payment_date']."%' AND transaction = 'REC' AND amount = '".$pr3['payment']."' AND branch_id = '".$_SESSION['login_branchid']."'");
	
	$delete_cbb = mysql_query("DELETE FROM cashbook WHERE package_id = '".$pr3['package_id']."' AND type = 'PAY' AND table_id = '0' AND receipt_no = '".$pr22['receipt_no']."' AND transaction = 'LOAN' AND amount = '".$pr22['balance']."' AND date LIKE '%".$pr3['payment_date']."%' AND branch_id = '".$_SESSION['login_branchid']."'");
	
	$delete_cbc = mysql_query("DELETE FROM cashbook WHERE package_id = '".$pr3['package_id']."' AND type = 'COMMISSION' AND table_id = '0' AND receipt_no = '".$pr22['receipt_no']."' AND transaction = 'KOMISYEN' AND amount = '".$inter."' AND date LIKE '%".$pr3['payment_date']."%' AND branch_id = '".$_SESSION['login_branchid']."'");
	
	//$new_loan = 0
	if($new_loan == '' || $new_loan == 0)
	{	
		$updateloanbal = mysql_query("UPDATE customer_loanpackage SET loan_amount = '".$pr3['balance']."', loan_total = '".$pr3['balance']."' loan_code = '".$pr3['receipt_no']."' WHERE id = '".$pr3['customer_loanid']."'");
	}
	
	//delete from balance transaction
	if($delete_cba)
	{
		//select from $bal1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr3['package_id']."' AND received = '".$pr3['payment']."' ORDER BY id DESC LIMIT 1");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		//delete from bal1 
		$delete_b1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
		
		if($delete_cbb)
	{
		//select from $bal1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr3['package_id']."' AND loan = '".$pr22['balance']."' ORDER BY id DESC LIMIT 1");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		//delete from bal1 
		$delete_b3 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
	}

//delete from balance rec
		if($delete_b1)
		{
			//select from bal2
			$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr3['package_id']."' AND received = '".$pr3['payment']."' AND bal_date LIKE '%".$pr3['payment_date']."%' ORDER BY id DESC LIMIT 1");
			$bal2 = mysql_fetch_assoc($bal2_q);
			
			//delete from bal2
			$delete_b2 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		}
		
			if($delete_b3)
		{
			//select from bal2
			$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr3['package_id']."' AND loan = '".$pr22['balance']."' AND month_receipt = '".$pr22['month_receipt']."'  ORDER BY id DESC LIMIT 1");
			$bal2 = mysql_fetch_assoc($bal2_q);
			
			//delete from bal2
			$delete_b4 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
			
			
			if($delete_b2)
			{
				//update payment_rec
				$update_q = mysql_query("UPDATE loan_payment_details SET payment = '', payment_date = '' WHERE id = '".$id."'");
			}
				if($delete_b4)
			{
				//update payment_rec
				$update_q2 = mysql_query("UPDATE loan_payment_details SET payment = '', payment_date = '' WHERE id = '".$id."'");
				
				
				//delete from loan_payment_details	(next payment)
	$delete_payrec2 = mysql_query("DELETE FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$loanid."'");
			
				if($update_q)
				{
					$msg .= 'Payment has been successfully delete from the record.<br>';
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				
				}
			}
		}
	}*/
}else
if($_POST['action'] == 'delete_payfixed4')//delete for payloan_CEK.php
{
	$id = $_POST['id'];
	$loanid = $_POST['loanid'];    
	
	//select payment record
	$pr_q = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$pr4 = mysql_fetch_assoc($pr_q);
	
		//delete from cashbook (received ,REC)
	$delete_cek1 = mysql_query("DELETE FROM cashbook WHERE package_id = '".$pr4['package_id']."' AND type = 'RECEIVED' AND table_id = '0' AND receipt_no = '".$pr4['receipt_no']."' AND amount = '".$pr4['payment']."' AND date LIKE '%".$pr4['payment_date']."%' AND transaction = 'REC'");
	
	//delete from cashbook (received ,CCM)
	$delete_cek2 = mysql_query("DELETE FROM cashbook WHERE package_id = '".$pr4['package_id']."' AND type = 'RECEIVED' AND table_id = '0' AND receipt_no = '".$pr4['receipt_no']."' AND amount = '".$pr4['payment']."' AND date LIKE '%".$pr4['payment_date']."%' AND transaction = 'CCM'");
	
	//delete from cashbook (received,INT)
	$delete_cek3 = mysql_query("DELETE FROM cashbook WHERE package_id = '".$pr4['package_id']."' AND type = 'RECEIVED' AND table_id = '0' AND receipt_no = '".$pr4['receipt_no']."' AND amount = '".$pr4['payment_int']."' AND date LIKE '%".$pr4['payment_date']."%' AND transaction = 'INT'");
	
	//$new_loan = 0 (Update total loan after delete)
	if($new_loan == '' || $new_loan == 0)
	{
		$update_bal_q = mysql_query("UPDATE loan_payment_details SET balance = '".$pr4['balance']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."' WHERE customer_loanid = '".$pr4['customer_loanid']."' AND id > '".$id."'");
		
	}
	
//delete from balance transaction
if($delete_cek1)
	{
		//select from $bal1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr4['package_id']."' AND received = '".$pr4['payment']."' AND date LIKE '%".$pr4['payment_date']."%' ORDER BY id DESC LIMIT 1");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		//delete from bal1 
		$delete_b1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");}

	if($delete_cek2)
	{
		//select from $bal1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr4['package_id']."' AND received = '".$pr4['payment']."' AND date LIKE '%".$pr4['payment_date']."%' ORDER BY id DESC LIMIT 1");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		//delete from bal1 
		$delete_b2 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
		
		if($delete_cek3)
	{
		//select from $bal1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr4['package_id']."' AND loan = '".$pr4['payment_int']."' AND date LIKE '%".$pr4['payment_date']."%' ORDER BY id DESC LIMIT 1");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		//delete from bal1 
		$delete_b3 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");
	}

//delete from balance rec
if($delete_b1)
		{
			//select from bal2
			$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr4['package_id']."' AND received = '".$pr4['payment']."' AND bal_date LIKE '%".$pr4['payment_date']."%' ORDER BY id DESC LIMIT 1");
			$bal2 = mysql_fetch_assoc($bal2_q);
			
			//delete from bal2
			$delete_b4 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		}
		if($delete_b2)
		{
			//select from bal2
			$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr4['package_id']."' AND received = '".$pr4['payment']."' AND bal_date LIKE '%".$pr4['payment_date']."%' ORDER BY id DESC LIMIT 1");
			$bal2 = mysql_fetch_assoc($bal2_q);
			
			//delete from bal2
			$delete_b5 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		}
		
			if($delete_b3)
		{
			//select from bal2
			$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr4['package_id']."' AND interest = '".$pr4['payment_int']."' AND bal_date LIKE '%".$pr4['payment_date']."%' ORDER BY id DESC LIMIT 1");
			$bal2 = mysql_fetch_assoc($bal2_q);
			
			//delete from bal2
			$delete_b6 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
			
			
			if($delete_b4)
			{
				//update payment_rec
				$update_q = mysql_query("UPDATE loan_payment_details SET payment = '', payment_date = '' WHERE id = '".$id."'");
			
			}
			if($delete_b5)
			{
				//update payment_rec
				$update_q = mysql_query("UPDATE loan_payment_details SET payment = '', payment_date = '' WHERE id = '".$id."'");
			
			}
				if($delete_b6)
			{
				//update payment_rec
				$update_q2 = mysql_query("UPDATE loan_payment_details SET payment = '', payment_date = '' WHERE id = '".$id."'");
				
				
				
				//delete from loan_payment_details	(next payment)
				$delete_payrec2 = mysql_query("DELETE FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$loanid."'");
			
			
				if($update_q)
				{
					$msg .= 'Payment has been successfully delete from the record.<br>';
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				
				}
			}
		}
	}
}else
if($_POST['action'] == 'delete_payfixed5')//delete for payloan_CEK.php
{
	$id = $_POST['id'];
	$loanid = $_POST['loanid'];
	$type = $_POST['type'];
	$transaction = $_POST['transaction'];
    
	//select payment record
	$pr_q = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$pr5 = mysql_fetch_assoc($pr_q);
	
	$update_lpd = mysql_query("UPDATE loan_payment_details SET payment_date = NULL, payment = NULL, staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now() WHERE id = '".$id."'");

		
	//delete from cashbook (received ,REC)
	$delete_cek1 = mysql_query("DELETE FROM cashbook WHERE package_id = '".$pr5['package_id']."' AND type = 'RECEIVED' AND table_id = '0' AND receipt_no = '".$pr5['receipt_no']."' AND amount = '".$pr5['payment']."' AND date LIKE '%".$pr5['payment_date']."%' AND transaction = 'REC'");
	
	//$new_loan = 0 (Update total loan after delete)
	if($new_loan == '' || $new_loan == 0)
	{
		$update_bal_q = mysql_query("UPDATE loan_payment_details SET balance = '".$pr5['balance']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."' WHERE customer_loanid = '".$pr5['customer_loanid']."' AND id > '".$id."'");

	}
	
//delete from balance transaction
if($delete_cek1)
	{
		//select from $bal1
		$bal1_q = mysql_query("SELECT * FROM balance_transaction WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr5['package_id']."' AND received = '".$pr5['payment']."' AND date LIKE '%".$pr5['payment_date']."%' ORDER BY id DESC LIMIT 1");
		$bal1 = mysql_fetch_assoc($bal1_q);
		
		//delete from bal1 
		$delete_b1 = mysql_query("DELETE FROM balance_transaction WHERE id = '".$bal1['id']."'");}

	{
	
//delete from balance rec
if($delete_cek1)
		{
			//select from bal2
			$bal2_q = mysql_query("SELECT * FROM balance_rec WHERE customer_loanid = '".$loanid."' AND package_id = '".$pr5['package_id']."' AND received = '".$pr5['payment']."' AND bal_date LIKE '%".$pr5['payment_date']."%' ORDER BY id DESC LIMIT 1");
			$bal2 = mysql_fetch_assoc($bal2_q);
			
			$delete_b1 = mysql_query("DELETE FROM balance_rec WHERE id = '".$bal2['id']."'");
		}
		{
			
			
			if($delete_b1)
			{
				//update payment_rec
				$update_q = mysql_query("UPDATE loan_payment_details SET payment = '', payment_date = '' WHERE id = '".$id."'");
			
			}
			if($delete_b5)
			{
				//update payment_rec
				$update_q = mysql_query("UPDATE loan_payment_details SET payment = '', payment_date = '' WHERE id = '".$id."'");
				
			}
				if($delete_b6)
			{
				//update payment_rec
				$update_q2 = mysql_query("UPDATE loan_payment_details SET payment = '', payment_date = '' WHERE id = '".$id."'");
				
				
				
				//delete from loan_payment_details	(next payment)
	$delete_payrec2 = mysql_query("DELETE FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$loanid."'");

			
				if($update_q)
				{
					$msg .= 'Payment has been successfully delete from the record.<br>';
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				
				}
			}
		}
	}
}
?>