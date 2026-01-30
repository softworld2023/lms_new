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
	
	//update new receipt
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$new_receipt."' WHERE id = '".$id."'");
	$update_q2 = mysql_query("UPDATE loan_payment_details SET receipt_no = '".$new_receipt."' WHERE customer_loanid = '".$id."'");
	$update_q3 = mysql_query("UPDATE cashbook SET receipt_no = '".$new_receipt."' WHERE table_id = '".$id."'");
	

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
	
	//update new receipt
	
	$update_q = mysql_query("UPDATE loan_payment_details SET month_receipt = '".$month_receipt."' WHERE customer_loanid = '".$id."'");
	$update_q2 = mysql_query("UPDATE balance_rec SET month_receipt = '".$month_receipt."' WHERE customer_loanid = '".$id."'");
	

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
if($_POST['action'] == 'update_aremarks')
{
	$id = $_POST['id'];
	$a_remarks = addslashes($_POST['remarks']);
	
	$update_q = mysql_query("UPDATE customer_account SET a_remarks = '".$a_remarks."' WHERE id = '".$id."'");
	
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
				$update_q = mysql_query("UPDATE loan_payment_details SET payment = '', payment_date = '' WHERE id = '".$id."'");
				
				//delete from loan_payment_details	(next payment)
	$delete_payrec = mysql_query("DELETE FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$loanid."'");
			
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
	}
}else
if($_POST['action'] == 'delete_payfixed3')//delete for payloan.php
{
	$id = $_POST['id'];
	$loanid = $_POST['loanid'];
	$package_id = $_POST['package_id'];
	
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
	}
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