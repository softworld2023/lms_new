<?php
session_start();

if ($_SESSION['login_branchid'] == '') {
	session_destroy();
	echo "<script type='text/javascript'>alert('Your Session Has Expired. Please re-login');</script>";
?>
	<meta http-equiv="refresh" content="0; url='../'">
<?php
}else
{
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");


/*if($_POST['action'] == 'payloanf')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$int_amount = $_POST['intamount'];
	$date = $_POST['date'];
	$period = $_POST['period'];
	$month = $_POST['month'];
	$next_paymentdate = $_POST['nextdate'];
	$receipt_no = $_POST['receipt']; 
	$month_receipt = $_POST['month_receipt'];
	
	$totalpaymentamount = $amount + $int_amount;
	
	$period_bal = $period - $month;
		
	$sql = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql_2 = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
	$get_lp = mysql_fetch_assoc($sql_2);
	
	$updatecustlp_q = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$receipt_no."' WHERE id = '".$get_q['customer_loanid']."'");
	
	$update_q = mysql_query("UPDATE loan_payment_details SET payment = '".$amount."', payment_int = '".$int_amount."', payment_date = '".$date."' WHERE id = '".$id."'");
		
	if($update_q)
	{
		if($amount > '0')
		{
			$cashbook_q = mysql_query("INSERT INTO cashbook SET type = 'RECEIVED', package_id = '".$get_q['package_id']."', table_id = '".$id."', transaction = 'REC', code = '".$get_lp['loan_code']."', receipt_no = '".$get_q['receipt_no']."', amount = '".$amount."', date = now()");
			
			$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', bal_date = now()");
		}
		
		if($int_amount > '0')
		{
			$cashbook_q = mysql_query("INSERT INTO cashbook SET type = 'RECEIVED', package_id = '".$get_q['package_id']."', table_id = '".$id."', transaction = 'REC', code = '".$get_lp['loan_code']."', receipt_no = '".$get_q['receipt_no']."', amount = '".$int_amount."', date = now()");
			
			$balance2_q2 = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', interest = '".$int_amount."', bal_date = now()");
		}
		
		$newmonth = $month + 1;
		
		if($newmonth <= $period)
		{
			$newbalance = $get_q['balance'] - $amount;
			$new_intbalance = $get_q['int_balance'] - $int_amount;
			
			if($newbalance > 0) 
			{
				if($amount == 0)
				{
					$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$int_amount."', date = now()");
					$balance1_q2 = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$int_amount."', date = now()");
					
					if($get_q['int_balance'] == $int_amount)
					{
						$new_intbalance = $int_amount;
					}
				}else
				{
					$balance1_q2 = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$amount."', date = now()");
				}
				
				if($new_intbalance > 0)
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '".$newbalance."', int_balance = '".$new_intbalance."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."'");
				
					if($insert_new)
					{
			
					$msg .= 'Payment has been successfully saved into record.<br>';
		
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
					}
				}else
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '".$newbalance."', int_balance = '".$new_intbalance."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."'");
				
					if($insert_new)
					{
				
						$msg .= 'Payment has been successfully saved into record.<br>';
			
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
					}
				}
			}
			else
			{
				$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = now()");
				$balance1_q2 = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$int_amount."', date = now()");
				
				if($new_intbalance > 0)
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '0', int_balance = '".$new_intbalance."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', reciept_no = '".$receipt_no."'");
				
					if($insert_new)
					{
				
						$msg .= 'Payment has been successfully saved into record.<br>';
			
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
					}
				}
				else
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '0', int_balance = '0', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."'");
				
					if($insert_new)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$get_q['customer_loanid']."'");
						
						if(update_lp)
						{
							$msg .= 'Payment has been successfully saved into record.<br>';
				
							$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						}
					
					}
				}
			}
		}else
		{
			$newbalance = $get_q['balance'] - $amount;
			$new_intbalance = $get_q['int_balance'] - $int_amount;
			
			if($newbalance > 0) 
			{
				if($amount == 0)
				{
					$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$int_amount."', date = now()");
					$balance1_q2 = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$int_amount."', date = now()");
					
					if($get_q['int_balance'] == $int_amount)
					{
						$new_intbalance = $int_amount;
					}
				}else
				{
					$balance1_q2 = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$amount."', date = now()");
				}
				
				if($new_intbalance > 0)
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '".$newbalance."', int_balance = '".$new_intbalance."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."'");
				
					if($insert_new)
					{
						$uperiod = $period + 1;
						
						$update_period = mysql_query("UPDATE customer_loanpackage SET loan_period = '".$uperiod."'");
						
						if($update_period)
						{
			
							$msg .= 'Payment has been successfully saved into record.<br>';
		
							$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						}
					}
				}else
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '".$newbalance."', int_balance = '".$new_intbalance."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."'");
				
					if($insert_new)
					{
				
						$uperiod = $period + 1;
						
						$update_period = mysql_query("UPDATE customer_loanpackage SET loan_period = '".$uperiod."'");
						
						if($update_period)
						{
			
							$msg .= 'Payment has been successfully saved into record.<br>';
		
							$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						}
					}
				}
			}
			else
			{
				$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = now()");
				$balance1_q2 = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$int_amount."', date = now()");
					
				if($new_intbalance > 0)
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '0', int_balance = '".$new_intbalance."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."'");
				
					if($insert_new)
					{
				
						$uperiod = $period + 1;
						
						$update_period = mysql_query("UPDATE customer_loanpackage SET loan_period = '".$uperiod."'");
						
						if($update_period)
						{
			
							$msg .= 'Payment has been successfully saved into record.<br>';
		
							$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						}
					}
				}
				else
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '0', int_balance = '0', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."'");
				
					if($insert_new)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$get_q['customer_loanid']."'");
						
						if(update_lp)
						{
							$msg .= 'Payment has been successfully saved into record.<br>';
				
							$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						}
					
					}
				}
			}
		}
	}
}else*/
if($_POST['action'] == 'payloanf2') //flexi tapi resit guna code yang sama
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$int_amount = $_POST['intamount'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	$period = $_POST['period'];
	$month = $_POST['month'];
	$next_paymentdate = date('Y-m-d', strtotime($_POST['nextdate']));
	$receipt_no = $_POST['receipt']; 
	//$month_receipt = $_POST['monthreceipt'];
	
	$month_receipt = date('Y-m', strtotime($_POST['date']));
	$new_loan = $_POST['newloan'];
	
	$sql = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql_2 = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
	$get_lp = mysql_fetch_assoc($sql_2);
	
	$loancode = $get_lp['loan_code'];
	
	$updatecustlp_q = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$receipt_no."' WHERE id = '".$get_q['customer_loanid']."'");
	
	$update_q = mysql_query("UPDATE loan_payment_details SET payment = '".$amount."', payment_int = '".$int_amount."', payment_date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', created_date = now() WHERE id = '".$id."'");
	
	//$new_loan = 0
	if($new_loan == '' || $new_loan == 0)
	{
		//loan balance
		$loan_balance = $get_q['balance'] - $amount;
		
		//$updateloanbal = mysql_query("UPDATE customer_loanpackage SET loan_amount = '".$loan_balance."', loan_total = '".$loan_balance."' WHERE id = '".$get_q['customer_loanid']."'");
		$updateloanbal = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_balance."' WHERE id = '".$get_q['customer_loanid']."'");

		//insert new row if $loan_balance != 0
		if($loan_balance > 0)
		{
			//if only pay for interest (tukar resit)
			if($amount == 0)
			{
						
				//insert to cashbook record the interest
				$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$int_amount."', transaction = 'KOMISYEN2', customer_id = '".$get_lp['customer_id']."', receipt_no = '".$receipt_no."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
				
				//insert to balance2 record the interest
				$balance2_ql = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', interest2 = '".$int_amount."', month_receipt = '".$month_receipt."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
				//check next month rec
				
				$nxmr_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['customer_loanid']."' AND id > '".$id."'");							
				$nxmr = mysql_fetch_assoc($nxmr_q);
				
				if(!$nxmr)
				{
					//insert new loan_payment_details
					$nm = $get_q['month'] + 1;
					$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '".$loan_balance."',next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				}
				else
				{
				
					$insert_lp_q = mysql_query("UPDATE loan_payment_details SET balance = '".$loan_balance."' WHERE id > '".$id."' AND customer_loanid = '".$get_q['customer_loanid']."' AND branch_id = '".$_SESSION['login_branchid']."'");
				}
				
				$msg .= 'Payment has been successfully saved into record.<br>';
				$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				
			}else
			{
				//insert to cashbook rec the actual amount paid
				$cashbook_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', amount = '".$amount."', transaction = 'CCM', code = '".$loancode."', receipt_no = '".$get_q['receipt_no']."', customer_id = '".$get_lp['customer_id']."', table_id = '".$id."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
				
				if($int_amount != 0)
				{				
					//insert to cashbook record the interest
					$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$int_amount."', transaction = 'KOMISYEN2', customer_id = '".$get_lp['customer_id']."', receipt_no = '".$receipt_no."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				}
							
				//insert into balance1 rec the actual amount paid
				$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
				//insert into balance2 rec the actual amount paid
				$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', month_receipt = '".$month_receipt."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
				
				//insert into balance2 rec the int amount paid
				if($int_amount != 0)
				{
					$balance2_qri = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', interest2 = '".$int_amount."', month_receipt = '".$month_receipt."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				}
				
				//check next month rec
				$nxmr_q = mysql_query("SELECT * FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$get_q['customer_loanid']."'");							
				$nxmr = mysql_fetch_assoc($nxmr_q);
				
				if(!$nxmr)
				{
					//insert new loan_payment_details
					$nm = $get_q['month'] + 1;
					$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '".$loan_balance."',next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
				}
				else
				{
					$insert_lp_q = mysql_query("UPDATE loan_payment_details SET balance = '".$loan_balance."' WHERE id > '".$id."' AND customer_loanid = '".$get_q['customer_loanid']."' AND branch_id = '".$_SESSION['login_branchid']."'");
				}
								
				$msg .= 'Payment has been successfully saved into record.<br>';
				$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				
			}
		}else
		{
			//insert to cashbook rec the actual amount paid
			$cashbook_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', amount = '".$amount."', transaction = 'CCM', code = '".$loancode."', receipt_no = '".$get_q['receipt_no']."', customer_id = '".$get_lp['customer_id']."', table_id = '".$id."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			if($int_amount != 0)
			{				
				//insert to cashbook record the interest
				$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$int_amount."', transaction = 'KOMISYEN2', customer_id = '".$get_lp['customer_id']."', receipt_no = '".$receipt_no."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
				
				//insert to bal2 record the interest
				$cashbook3_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', interest2 = '".$int_amount."',  month_receipt = '".$month_receipt."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			}
						
			//insert into balance1 rec the actual amount paid
			$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
		
			//insert into balance2 rec the actual amount paid
			$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', month_receipt = '".$month_receipt."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			
			//check next month rec
			$nxmr_q = mysql_query("SELECT * FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$get_q['customer_loanid']."'");							
			$nxmr = mysql_fetch_assoc($nxmr_q);
			
			if(!$nxmr)
			{		
				//insert new loan_payment_details
				$nm = $get_q['month'] + 1;				
			
				$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '0', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
			}
			else
			{
				$insert_lp_q = mysql_query("UPDATE loan_payment_details SET balance = '".$loan_balance."', receipt_no = '".$receipt_no."' WHERE id > '".$id."' AND customer_loanid = '".$get_q['customer_loanid']."' AND branch_id = '".$_SESSION['login_branchid']."'");
			}			
			
			
			//set loan = FINISHED
			$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$get_q['customer_loanid']."'");
			
			$msg .= 'Payment has been successfully saved into record.<br>';
			$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		}
	}else //$new_loan != 0
	{	
		if($int_amount != 0)
		{
			$newinterest = $int_amount;
		}else
		{
			$percentint = $get_q['int_percent'] / 100;
			//calculate new interest
			$newinterest = $percentint * $new_loan;
		}
		
		if($amount != 0)
		{
			//insert to cashbook (transaction shows that settle the loan)
			$cashbook_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', amount = '".$amount."', transaction = 'REC', code = '".$loancode."', receipt_no = '".$get_q['receipt_no']."', customer_id = '".$get_lp['customer_id']."', table_id = '".$id."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			//insert to cashbook (add new loan to record)
			$cashbook2_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'PAY', amount = '".$new_loan."', transaction = 'LOAN', code = '".$loancode."', receipt_no = '".$receipt_no."', customer_id = '".$get_lp['customer_id']."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			//insert into cashbook interest transaction
			$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$newinterest."', transaction = 'KOMISYEN', receipt_no = '".$receipt_no."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			//insert into balance1 new loan amount
			$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$new_loan."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', date = '".$date."'");
			
			//insert into balance1 rec the actual amount paid
			$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', date = '".$date."'");
			
			//insert into balance2 rec the actual amount paid
			//$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', month_receipt = '".$get_q['month_receipt']."', bal_date = '".$date."'");
			$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', month_receipt = '".$month_receipt."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			//insert into balance2 rec the new loan amount
			$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$new_loan."', month_receipt = '".$month_receipt."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			
			//check next month rec
			$nxmr_q = mysql_query("SELECT * FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$get_q['customer_loanid']."'");							
			$nxmr = mysql_fetch_assoc($nxmr_q);
			
			if(!$nxmr)
			{	
				//insert new loan_payment_details
				$nm = $get_q['month'] + 1;
				$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '".$new_loan."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
			}
			else
			{
				$insert_lp_q = mysql_query("UPDATE loan_payment_details SET balance = '".$new_loan."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."' WHERE id > '".$id."' AND customer_loanid = '".$get_q['customer_loanid']."'");
			}	
			
			
			//$updateloanbal = mysql_query("UPDATE customer_loanpackage SET loan_amount = '".$new_loan."', loan_total = '".$new_loan."' WHERE id = '".$get_q['customer_loanid']."'");
			$updateloanbal = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$new_loan."' WHERE id = '".$get_q['customer_loanid']."'");
			
			$msg .= 'Payment has been successfully saved into record.<br>';
			$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		}else
		{// just add loan no payment
			
			//insert to cashbook (add new loan to record)
			$cashbook2_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'PAY', amount = '".$new_loan."', transaction = 'EXT', code = '".$loancode."', receipt_no = '".$receipt_no."', customer_id = '".$get_lp['customer_id']."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			//insert into cashbook interest transaction
			$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$newinterest."', transaction = 'KOMISYEN', receipt_no = '".$receipt_no."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			//insert into balance1 new loan amount
			$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$new_loan."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			//insert into balance1 rec the actual amount paid
			$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			//insert into balance2 rec the new loan amount
			$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$new_loan."', month_receipt = '".$month_receipt."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			$nbalance = $new_loan + $get_q['balance'];
			
			if(!$nxmr)
			{	
				//insert new loan_payment_details
				$nm = $get_q['month'] + 1;
				$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '".$nbalance."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
			}
			else
			{
				$insert_lp_q = mysql_query("UPDATE loan_payment_details SET balance = '".$loan_balance."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."' WHERE id > '".$id."' AND customer_loanid = '".$get_q['customer_loanid']."'");
			}	
			
			//$updateloanbal = mysql_query("UPDATE customer_loanpackage SET loan_amount = '".$nbalance."', loan_total = '".$nbalance."' WHERE id = '".$get_q['customer_loanid']."'");
			$updateloanbal = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$nbalance."' WHERE id = '".$get_q['customer_loanid']."'");
			
			$msg .= 'Payment has been successfully saved into record.<br>';
			$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		}
	}	
}else
if($_POST['action'] == 'payloanf')//tukar resit
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$int_amount = $_POST['intamount'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	$period = $_POST['period'];
	$month = $_POST['month'];
	$next_paymentdate = date('Y-m-d', strtotime($_POST['nextdate']));
	$receipt_no = $_POST['receipt']; 
	$month_receipt = $_POST['monthreceipt'];
	$new_loan = $_POST['newloan'];
	
	$sql = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql_2 = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
	$get_lp = mysql_fetch_assoc($sql_2);
	
	$loancode = $get_lp['loan_code'];
	
	$updatecustlp_q = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$receipt_no."' WHERE id = '".$get_q['customer_loanid']."'");
	
	$update_q = mysql_query("UPDATE loan_payment_details SET payment = '".$amount."', payment_int = '".$int_amount."', payment_date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', created_date = now() WHERE id = '".$id."'");
	
	//$new_loan = 0
	if($new_loan == '' || $new_loan == 0)
	{
		//loan balance
		$loan_balance = $get_q['balance'] - $amount;
		
		$updateloanbal = mysql_query("UPDATE customer_loanpackage SET loan_amount = '".$loan_balance."', loan_total = '".$loan_balance."' WHERE id = '".$get_q['customer_loanid']."'");

		//insert new row if $loan_balance != 0
		if($loan_balance > 0)
		{
			//if only pay for interest (tukar resit)
			if($amount == 0)
			{
			
				if($get_q['month_receipt'] != $month_receipt)
				{			
					//insert to cashbook (transaction shows that settle the loan but actually only pay for the interest (tukar resit)
					$cashbook_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', amount = '".$loan_balance."', transaction = 'REC', code = '".$loancode."', receipt_no = '".$get_q['receipt_no']."', customer_id = '".$get_lp['customer_id']."', table_id = '".$id."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
					
					//insert to cashbook pay the loan (new receipt no)
					$cashbook2_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'PAY', amount = '".$loan_balance."', transaction = 'LOAN', code = '".$loancode."', receipt_no = '".$receipt_no."', customer_id = '".$get_lp['customer_id']."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
					
					//insert into balance1 (transaction shows that settle the loan but actually only pay for the interest (tukar resit)
				$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$loan_balance."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
					//insert to balance1 pay the loan (new receipt no)
					$balance1_ql = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$loan_balance."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
					//insert into balance2 (transaction shows that settle the loan but actually only pay for the interest (tukar resit)
					$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$loan_balance."', month_receipt = '".$get_q['month_receipt']."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
					//insert to balance2 pay the loan (new receipt no)
					$balance2_ql = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$loan_balance."', month_receipt = '".$month_receipt."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				}
				
				//insert to cashbook record the interest
				$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$int_amount."', transaction = 'INT', customer_id = '".$get_lp['customer_id']."', code = '".$receipt_no."', receipt_no = '".$receipt_no."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
				
				//check next month rec
				$nxmr_q = mysql_query("SELECT * FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$get_q['customer_loanid']."'");							
				$nxmr = mysql_fetch_assoc($nxmr_q);
				
				if(!$nxmr)
				{	
					//insert new loan_payment_details
					$nm = $get_q['month'] + 1;
					$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '".$loan_balance."',next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				}else
				{
					//update receipt no
					$insert_lp_q = mysql_query("UPDATE loan_payment_details SET balance = '".$loan_balance."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."' WHERE customer_loanid = '".$get_q['customer_loanid']."' AND id > '".$id."'");

				}
				
				$msg .= 'Payment has been successfully saved into record.<br>';
				$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				
			}else
			{
				//insert to cashbook rec the actual amount paid
				$cashbook_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', amount = '".$amount."', transaction = 'CCM', code = '".$loancode."', receipt_no = '".$get_q['receipt_no']."', customer_id = '".$get_lp['customer_id']."', table_id = '".$id."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
				
				if($int_amount != 0)
				{				
					//insert to cashbook record the interest
					$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$int_amount."', transaction = 'KOMISYEN', receipt_no = '".$receipt_no."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
				}
							
				//insert into balance1 rec the actual amount paid
				$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
				//insert into balance2 rec the actual amount paid
				$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', month_receipt = '".$get_q['month_receipt']."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
				
				//check next month rec
				$nxmr_q = mysql_query("SELECT * FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$get_q['customer_loanid']."'");							
				$nxmr = mysql_fetch_assoc($nxmr_q);
				
				if(!$nxmr)
				{	
					//insert new loan_payment_details
					$nm = $get_q['month'] + 1;
					
					$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '".$loan_balance."',next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				}else
				{
					//update receipt no
					$insert_lp_q = mysql_query("UPDATE loan_payment_details SET balance = '".$loan_balance."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."' WHERE customer_loanid = '".$get_q['customer_loanid']."' AND id > '".$id."'");

				}
				
				
				$msg .= 'Payment has been successfully saved into record.<br>';
				$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				
			}
		}else
		{
			//insert to cashbook rec the actual amount paid
			$cashbook_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', amount = '".$amount."', transaction = 'REC', code = '".$loancode."', receipt_no = '".$get_q['receipt_no']."', customer_id = '".$get_lp['customer_id']."', table_id = '".$id."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			if($int_amount != 0)
			{				
				//insert to cashbook record the interest
				$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$int_amount."', transaction = 'KOMISYEN', receipt_no = '".$receipt_no."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			}
						
			//insert into balance1 rec the actual amount paid
			$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
		
			//insert into balance2 rec the actual amount paid
			$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', month_receipt = '".$get_q['month_receipt']."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			//insert new loan_payment_details
			$nm = $get_q['month'] + 1;
			
			
			//check next month rec
				$nxmr_q = mysql_query("SELECT * FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$get_q['customer_loanid']."'");							
				$nxmr = mysql_fetch_assoc($nxmr_q);
				
				if(!$nxmr)
				{	
					$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '0', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				}else
				{
					//update receipt no
					$insert_lp_q = mysql_query("UPDATE loan_payment_details SET balance = '".$loan_balance."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."' WHERE customer_loanid = '".$get_q['customer_loanid']."' AND id > '".$id."'");

				}
			
			$updateloanbal = mysql_query("UPDATE customer_loanpackage SET loan_amount = '0', loan_total = '0' WHERE id = '".$get_q['customer_loanid']."'");
			
			//set loan = FINISHED
			$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$get_q['customer_loanid']."'");
			
			$msg .= 'Payment has been successfully saved into record.<br>';
			$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		}
	}else //$new_loan != 0
	{	
		if($int_amount != 0)
		{
			$newinterest = $int_amount;
		}else
		{
			$percentint = $get_q['int_percent'] / 100;
			//calculate new interest
			$newinterest = ceil($percentint * $new_loan);
		}
		
		if($amount != 0)
		{
			//insert to cashbook (transaction shows that settle the loan)
			$cashbook_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', amount = '".$amount."', transaction = 'REC', code = '".$loancode."', receipt_no = '".$get_q['receipt_no']."', customer_id = '".$get_lp['customer_id']."', table_id = '".$id."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			//insert to cashbook (add new loan to record)
			$cashbook2_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'PAY', amount = '".$new_loan."', transaction = 'LOAN', code = '".$loancode."', receipt_no = '".$receipt_no."', customer_id = '".$get_lp['customer_id']."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			//insert into cashbook interest transaction
			$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$newinterest."', transaction = 'KOMISYEN', receipt_no = '".$receipt_no."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			//insert into balance1 new loan amount
			$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$new_loan."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			//insert into balance1 rec the actual amount paid
			$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			//insert into balance2 rec the actual amount paid
			$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', month_receipt = '".$get_q['month_receipt']."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			//insert into balance2 rec the new loan amount
			$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$new_loan."', month_receipt = '".$month_receipt."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			//check next month rec
			$nxmr_q = mysql_query("SELECT * FROM loan_payment_details WHERE id > '".$id."' AND customer_loanid = '".$get_q['customer_loanid']."'");							
			$nxmr = mysql_fetch_assoc($nxmr_q);
			
			if(!$nxmr)
			{	
				//insert new loan_payment_details
				$nm = $get_q['month'] + 1;
				$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '".$new_loan."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			}else
			{
				//update receipt no
				$insert_lp_q = mysql_query("UPDATE loan_payment_details SET balance = '".$new_loan."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."' WHERE customer_loanid = '".$get_q['customer_loanid']."' AND id > '".$id."'");

			}
			
			
			$updateloanbal = mysql_query("UPDATE customer_loanpackage SET loan_amount = '".$new_loan."', loan_total = '".$new_loan."' WHERE id = '".$get_q['customer_loanid']."'");
			
			$msg .= 'Payment has been successfully saved into record.<br>';
			$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		}else
		{// just add loan no payment
			
			//insert to cashbook (add new loan to record)
			$cashbook2_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'PAY', amount = '".$new_loan."', transaction = 'EXT', code = '".$loancode."', receipt_no = '".$receipt_no."', customer_id = '".$get_lp['customer_id']."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			//insert into cashbook interest transaction
			$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$newinterest."', transaction = 'KOMISYEN', receipt_no = '".$receipt_no."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			//insert into balance1 new loan amount
			$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$new_loan."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', date = '".$date."'");
			
			//insert into balance1 rec the actual amount paid
			$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', date = '".$date."'");
			
			//insert into balance2 rec the new loan amount
			$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$new_loan."', month_receipt = '".$month_receipt."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			$nbalance = $new_loan + $get_q['balance'];
			
			//insert new loan_payment_details
			$nm = $get_q['month'] + 1;
			$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '".$nbalance."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			
			$updateloanbal = mysql_query("UPDATE customer_loanpackage SET loan_amount = '".$nbalance."', loan_total = '".$nbalance."' WHERE id = '".$get_q['customer_loanid']."'");
			
			$msg .= 'Payment has been successfully saved into record.<br>';
			$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		}
	}	
}else

if($_POST['action'] == 'delete_payment')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$payment_date = $_POST['payment_date'];
	
	//get payment

	$sk_q = mysql_query("SELECT * FROM skim_kutu WHERE id = '".$id."'");
	$sk = mysql_fetch_assoc($sk_q);
	
	$skp_q = mysql_query("SELECT * FROM skimkutu_payment WHERE id = '".$id."'");
	$skp = mysql_fetch_assoc($skp_q);
	
	//$cbsk_q = mysql_query("SELECT * FROM cashbook_skimkutu WHERE id = '".$id."'");
	//$cbsk = mysql_fetch_assoc($cbsk_q);
	
	
	//delete record in table skimkutu_payment
	
	$delete_q2 = mysql_query("DELETE FROM cashbook_skimkutu WHERE package_id = '".$skp['skim_id']."' AND outamt = '".$skp['amount']."' AND date = '".$skp['payment_date']."'");
	
	$delete_q = mysql_query("DELETE FROM skimkutu_payment WHERE skim_id = '".$skp['skim_id']."' AND amount = '".$skp['amount']."' AND payment_date = '".$skp['payment_date']."' ");

	if($delete_q && $delete_q2)
	{
		$_SESSION['msg'] = "<div class='success'>Payment has been successfully deleted from database.</div>";	
	}
}else
if($_POST['action'] == 'delete_lateint')
{
	$id = $_POST['id'];
	$lid = $_POST['lid'];
	$amount = $_POST['amount'];
	$package_id = $_POST['package_id'];
	$date = $_POST['date'];
	$payment_date = $_POST['payment_date'];
	$rcpmth = date('Y-m', strtotime($date));
	
	
	
	//late interest payment details
	$lipd_q = mysql_query("SELECT * FROM late_interest_payment_details WHERE id = '".$id."'");
	$lipd = mysql_fetch_assoc($lipd_q);
	
	//balance rec
	$br_q = mysql_query("SELECT * FROM balance_rec WHERE id = '".$id."'");
	$br = mysql_fetch_assoc($br_q);
	
	//cashbook
	$cb_q = mysql_query("SELECT * FROM cashbook WHERE table_id = '".$id."'");
	$cb = mysql_fetch_assoc($cb_q);

	//late interest record
	$lir_q = mysql_query("SELECT * FROM late_interest_record WHERE id = '".$lid."'");
	$lir = mysql_fetch_assoc($lir_q);
	
	//cust info
	$cust_q = mysql_query("SELECT * FROM customer_details WHERE id = '".$lir['customer_id']."'");
	$cust = mysql_fetch_assoc($cust_q);
	
	//check package receipt(update balance at late interest record)
	$rt_q = mysql_query("SELECT * FROM loan_scheme WHERE id = '".$package_id."'");
	$get_rt = mysql_fetch_assoc($rt_q);
	
	$trans = "LATE INT - ".$cust['name']." - ".$lir['loan_code'];

	$balance = $lir['balance'] + $amount;
	
	if($balance < 0)
	{
		$balance = 0;
	}	
		
		$delete_b2q = mysql_query("DELETE FROM balance_rec WHERE customer_loanid = '0' AND package_id = '".$lir['package_id']."' AND interest = '".$lipd['amount']."' AND bal_date LIKE '%".$lipd['payment_date']."%' AND branch_id = '".$lir['branch_id']."'");
		
	if($delete_b2q)
			{
		//delete from cashbook
		$delete_c_q = mysql_query("DELETE FROM cashbook WHERE table_id = '".$lipd['lid']."' AND amount = '".$lipd['amount']."' AND date LIKE '%".$lipd['payment_date']."%' AND type = 'RECEIVED2' ");
		
		//delete payment record
	$delete_q = mysql_query("DELETE FROM late_interest_payment_details WHERE id = '".$id."'");
	
	if($delete_q)
	{
		//update late interest record
		$update = mysql_query("UPDATE late_interest_record SET balance = '".$balance."' WHERE id = '".$lid."'");
					
					$_SESSION['msg'] = "<div class='success'>Late Interest Payment has been successfully deleted from the database.</div>";
					echo "<script>window.parent.location='payLateInt.php?id=".$lid."'</script>";
				}else
				{
				$_SESSION['msg'] = "<div class='success'>Late Interest Payment has been successfully deleted from the database.</div>";
					echo "<script>window.parent.location='payLateInt.php?id=".$lid."'</script>";
				}
			
			}
}else
if(isset($_POST['edit_scheme']))
{
	$id = $_POST['id'];
	$scheme1 = stripslashes($_POST['scheme']);
	$scheme = strtoupper(mysql_real_escape_string($scheme1));
	$formula1 = stripslashes($_POST['formula']);
	$formula = mysql_real_escape_string($formula1);
	$interest = $_POST['interest'];
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$type = $_POST['type'];
	$receipt_type = $_POST['receipt_type'];
	
	//insert new scheme
	$insert_q = mysql_query("UPDATE loan_scheme SET scheme = '".$scheme."', formula = '".$formula."', interest = '".$interest."', from_date = '".$from_date."', to_date = '".$to_date."', type = '".$type."', receipt_type = '".$receipt_type."' WHERE id = '".$id."'");
	
	if($insert_q)
	{
		$_SESSION['msg'] = "<div class='success'>Package info has been successfully updated.</div>";
		echo "<script>window.location='../package/'</script>";
	}
}
if($_POST['action'] == 'payloan')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	$period = $_POST['period'];
	$month = $_POST['month'];
	$next_paymentdate = date('Y-m-d', strtotime($_POST['nextdate']));
	$receipt_no = $_POST['receipt'];
	$prev_receipt = $_POST['prev_receipt'];
	
	//get the actual payment date if late payment
	$select = mysql_query("SELECT * FROM loan_payment_details WHERE receipt_no = '".$prev_receipt."' ORDER BY id ASC");
	$get_select = mysql_fetch_assoc($select);
	$num_select = mysql_num_rows($select);
	
	$period_bal = $period - $month;
	
	$sql = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql_2 = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
	$get_lp = mysql_fetch_assoc($sql_2);
	
	$min_int = $get_q['interest'] - $get_select['interest'];
	
	//monthly 
	$m_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['customer_loanid']."' AND month = '1'");
	$get_mq = mysql_fetch_assoc($m_q);
	
	$adjustment_q = mysql_query("SELECT * FROM adjustment WHERE customer_loanid = '".$get_q['customer_loanid']."' AND customer_paymentid = '".$id."'");
	$get_a = mysql_fetch_assoc($adjustment_q);
	$ar = mysql_num_rows($adjustment_q);
	
	$actamt = $amount - $min_int;
		
	if($ar == 0)
	{
		$update_q = mysql_query("UPDATE loan_payment_details SET payment = '".$amount."', payment_date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', created_date = now() WHERE id = '".$id."'");
	
		if($update_q)
		{
			$cashbook_q = mysql_query("INSERT INTO cashbook SET type = 'RECEIVED', package_id = '".$get_q['package_id']."', table_id = '".$id."', transaction = 'REC', code = '".$get_lp['loan_code']."', receipt_no = '".$get_q['receipt_no']."', customer_id = '".$get_lp['customer_id']."', amount = '".$amount."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			/*if($num_select == '1')
			{*/
				$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			/*}
			else
			{
				$actamt = $amount - $min_int;
				$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$actamt."', bal_date = '".$date."'");
				$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$actamt."', date = '".$date."'");
			}*/
			
			//check next record 
			$nextp_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['customer_loanid']."' AND id > '".$id."' ");
			$nextp = mysql_fetch_assoc($nextp_q);
			
			$newmonth = $month + 1;if(!$nextp)
			{
				//if($newmonth <= $period)
				//{
					$newbalance = $get_q['balance'] - $amount;
					
					$new_monthlypayment1 = $newbalance / $period_bal;
					$new_monthlypayment = round($new_monthlypayment1);
					$new_totint = $get_q['interest'] * $period_bal;
					
					$pokok_bal = $newbalance - $new_totint;
					$pokok1 = $pokok_bal / $period_bal;
					$pokok = round($pokok1);
					
					$monthlypay = $newbalance / $period_bal;
					$monthly = round($monthlypay);
					
					if($newbalance > 0)
					{
						$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '".$newbalance."', monthly = '".$get_mq['monthly']."', pokok = '".$pokok."', interest = '".$get_q['interest']."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
						
						if($insert_new)
						{
					
							$msg .= 'Payment has been successfully saved into record.<br>';
				
							$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						}
					}
					else
					{
						$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', balance = '0', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
						
						if($insert_new)
						{
							$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$get_q['customer_loanid']."'");
							
							if(update_lp)
							{
								$msg .= 'Payment has been successfully saved into record.<br>';
					
								$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
							}
						}
					}
				/* }
				else {
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', balance = '0', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
						
					if($insert_new)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$get_q['customer_loanid']."'");
						
						if(update_lp)
						{
							$msg .= 'Payment has been successfully saved into record.<br>';
				
							$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						}
					}
				} */
			}else
			{
				$newbalance = $get_q['balance'] - $amount;
					
				$new_monthlypayment1 = $newbalance / $period_bal;
				$new_monthlypayment = round($new_monthlypayment1);
				$new_totint = $get_q['interest'] * $period_bal;
				
				$pokok_bal = $newbalance - $new_totint;
				$pokok1 = $pokok_bal / $period_bal;
				$pokok = round($pokok1);
				
				// update balance
				$insert_new = mysql_query("UPDATE loan_payment_details SET balance = '".$newbalance."', monthly = '".$get_mq['monthly']."', pokok = '".$pokok."', interest = '".$get_q['interest']."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."' WHERE customer_loanid = '".$get_q['customer_loanid']."' AND id > '".$id."'");
				
				$msg .= 'Payment has been successfully saved into record.<br>';
					
				$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
			}
		}
	}else
	{
		$update_q = mysql_query("UPDATE loan_payment_details SET payment = '".$amount."', payment_date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now() WHERE id = '".$id."'");
		
		if($update_q)
		{
			$cashbook_q = mysql_query("INSERT INTO cashbook SET type = 'RECEIVED', package_id = '".$get_q['package_id']."', table_id = '".$id."', transaction = 'REC', code = '".$get_lp['loan_code']."', amount = '".$amount."', customer_id = '".$get_lp['customer_id']."', receipt_no = '".$get_q['receipt_no']."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			/*if($num_select == '1')
			{*/
				$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			/*}
			else
			{
				$actamt = $amount - $min_int;
				$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$actamt."', bal_date = '".$get_select['next_paymentdate']."'");
				$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$actamt."', date = '".$date."'");
			}*/
			
			$newmonth = $month + 1;
			
			if($newmonth <= $period)
			{
				$newbalance = $get_q['balance'] - $amount;
				
				
				$new_monthlypayment1 = $newbalance / $period_bal;
				$new_monthlypayment = round($new_monthlypayment1);
				
				if($get_a['add_on'] != 0)
				{
					$new_totint = ($get_q['interest'] - $get_a['add_on']) * $period_bal;
				}
				if($get_a['reduce'] != 0)
				{
					$new_totint = ($get_q['interest'] + $get_a['reduce']) * $period_bal;
				}
				
				$pokok_bal = $newbalance - $new_totint;
				$pokok1 = $pokok_bal / $period_bal;
				$pokok = round($pokok1);
				
				$monthlypay = $newbalance / $period_bal;
				$monthly = round($monthlypay);
				
				$intmonthly = round($new_totint / $period_bal);
				
				if($newbalance > 0)
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '".$newbalance."', monthly = '".$get_mq['monthly']."', pokok = '".$pokok."', interest = '".$intmonthly."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
					if($insert_new)
					{
				
						$msg .= 'Payment has been successfully saved into record.<br>';
			
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
					}
				}
				else
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', balance = '0', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
					if($insert_new)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$get_q['customer_loanid']."'");
						
						if(update_lp)
						{
							$msg .= 'Payment has been successfully saved into record.<br>';
				
							$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						}
					}
				}
			}
		}
	}
}else
if($_POST['action'] == 'update_period')
{
	$id = $_POST['id'];
	$newP = $_POST['newP'];
	$oldP = $_POST['oldP'];
	
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_period = '".$newP."' WHERE id = '".$id."'");
	
	if($update_q)
	{
		$msg .= 'Loan period has been successfully updated.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
}else
if(isset($_POST['adjust_loan']))
{
	$loan_id = $_POST['loan_id'];
	$payment_id = $_POST['payment_id'];
	$month = $_POST['loan_period'];
	$add_on = $_POST['add_on'];
	$reduce = $_POST['reduce'];
	$reason = addslashes($_POST['reason']);
	
	$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$loan_id."'");
	$get_l = mysql_fetch_assoc($loan_q);
	
	$payment_q = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$payment_id."'");
	$get_p = mysql_fetch_assoc($payment_q);
	
	$bal_month = $month - $get_p['month'] + 1;
	$prev_balmonth = $get_l['loan_period'] - $get_p['month'] + 1;
	
	$adjustment_q = mysql_query("SELECT * FROM adjustment WHERE customer_loanid = '".$loan_id."' AND customer_paymentid = '".$payment_id."'");
	$get_a = mysql_fetch_assoc($adjustment_q);
	$ar = mysql_num_rows($adjustment_q);
	
	if($ar != 0)
	{
		if($get_l['loan_period'] != $month)
		{
			$update_l = mysql_query("UPDATE customer_loanpackage SET loan_period = '".$month."' WHERE id = '".$loan_id."'");
			
			if($update_l)
			{
				if($add_on != '' && $reduce == '')
				{
					$new_intpermonth = $get_p['interest'] + $add_on;
					$new_monthly = $get_p['monthly'] + $add_on;
					$new_totbal = $get_p['balance'] + $add_on;
					$loan_total = $get_l['loan_total'] + $add_on;
				}else			
				if($reduce != '' && $add_on == '')
				{
					$new_intpermonth = $get_p['interest'] - $reduce;
					$new_monthly = $get_p['monthly'] - $reduce;
					$new_totbal = $get_p['balance'] - $reduce;
					$loan_total = $get_l['loan_total'] - $reduce;
				}else
				{
					$new_intbal = $int_bal;
					$new_intpermonth = $get_p['interest'];
					$new_monthly = $get_p['monthly'];
					$new_totbal = $get_p['balance'];
					$loan_total = $get_l['loan_total'];
				}
					
				$update_q = mysql_query("UPDATE loan_payment_details SET interest = '".$new_intpermonth."', balance = '".$new_totbal."', monthly = '".$new_monthly."' WHERE id = '".$payment_id."'");
				
				if($update_q)
				{
					$insert_q = mysql_query("UPDATE adjustment SET month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."' WHERE customer_loanid = '".$loan_id."' AND customer_paymentid = '".$payment_id."'");
					if($insert_q)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
						
						if($update_lp)
						{
							$msg .= 'Loan period has been successfully updated.<br>';
						
							if(strpos($get_l['loan_package'], 'SKIM F') === FALSE)
							{
								echo "<script>window.parent.location='payloan_a.php?id=$loan_id'</script>";
							}else
							{
								echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
							}
						}
					}
				}
			}
		}else
		{
			if($add_on != '' && $reduce == '')
			{
				$new_intpermonth = $get_p['interest'] + $add_on;
				$new_monthly = $get_p['monthly'] + $add_on;
				$new_totbal = $get_p['balance'] + $add_on;
				$loan_total = $get_l['loan_total'] + $add_on;
			}else			
			if($reduce != '' && $add_on == '')
			{
				$new_intpermonth = $get_p['interest'] - $reduce;
				$new_monthly = $get_p['monthly'] - $reduce;
				$new_totbal = $get_p['balance'] - $reduce;
				$loan_total = $get_l['loan_total'] - $reduce;
			}else
			{
				$new_intbal = $int_bal;
				$new_intpermonth = $get_p['interest'];
				$new_monthly = $get_p['monthly'];
				$new_totbal = $get_p['balance'];
				$loan_total = $get_l['loan_total'];
			}
				
			$update_q = mysql_query("UPDATE loan_payment_details SET interest = '".$new_intpermonth."', balance = '".$new_totbal."', monthly = '".$new_monthly."' WHERE id = '".$payment_id."'");
			
			if($update_q)
			{
				$insert_q = mysql_query("UPDATE adjustment SET month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."' WHERE customer_loanid = '".$loan_id."' AND customer_paymentid = '".$payment_id."'");
				if($insert_q)
				{
					$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
					if($update_lp)
					{
						$msg .= 'Loan period has been successfully updated.<br>';
					
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						
						if(strpos($get_l['loan_package'], 'SKIM F') === FALSE)
						{
							echo "<script>window.parent.location='payloan_a.php?id=$loan_id'</script>";
						}else
						{
							echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
						}
					}
				}
			}
		}
	}else
	{
		if($get_l['loan_period'] != $month)
		{
			$update_l = mysql_query("UPDATE customer_loanpackage SET loan_period = '".$month."' WHERE id = '".$loan_id."'");
			
			if($update_l)
			{
				if($add_on != '' && $reduce == '')
				{
					$new_intpermonth = $get_p['interest'] + $add_on;
					$new_monthly = $get_p['monthly'] + $add_on;
					$new_totbal = $get_p['balance'] + $add_on;
					$loan_total = $get_l['loan_total'] + $add_on;
				}else			
				if($reduce != '' && $add_on == '')
				{
					$new_intpermonth = $get_p['interest'] - $reduce;
					$new_monthly = $get_p['monthly'] - $reduce;
					$new_totbal = $get_p['balance'] - $reduce;
					$loan_total = $get_l['loan_total'] - $reduce;
				}else
				{
					$new_intbal = $int_bal;
					$new_intpermonth = $get_p['interest'];
					$new_monthly = $get_p['monthly'];
					$new_totbal = $get_p['balance'];
					$loan_total = $get_l['loan_total'];
				}
					
				$update_q = mysql_query("UPDATE loan_payment_details SET interest = '".$new_intpermonth."', balance = '".$new_totbal."', monthly = '".$new_monthly."' WHERE id = '".$payment_id."'");
				
				if($update_q)
				{
					$insert_q = mysql_query("INSERT INTO adjustment SET customer_loanid = '".$loan_id."', customer_paymentid = '".$payment_id."', month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."'");
					if($insert_q)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
						
						if($update_lp)
						{
							$msg .= 'Loan period has been successfully updated.<br>';
						
							if(strpos($get_l['loan_package'], 'SKIM F') === FALSE)
							{
								echo "<script>window.parent.location='payloan_a.php?id=$loan_id'</script>";
							}else
							{
								echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
							}
						}
					}
				}
			}
		}else
		{
			if($add_on != '' && $reduce == '')
			{
				$new_intpermonth = $get_p['interest'] + $add_on;
				$new_monthly = $get_p['monthly'] + $add_on;
				$new_totbal = $get_p['balance'] + $add_on;
				$loan_total = $get_l['loan_total'] + $add_on;
			}else			
			if($reduce != '' && $add_on == '')
			{
				$new_intpermonth = $get_p['interest'] - $reduce;
				$new_monthly = $get_p['monthly'] - $reduce;
				$new_totbal = $get_p['balance'] - $reduce;
				$loan_total = $get_l['loan_total'] - $reduce;
			}else
			{
				$new_intbal = $int_bal;
				$new_intpermonth = $get_p['interest'];
				$new_monthly = $get_p['monthly'];
				$new_totbal = $get_p['balance'];
				$loan_total = $get_l['loan_total'];
			}
				
			$update_q = mysql_query("UPDATE loan_payment_details SET interest = '".$new_intpermonth."', balance = '".$new_totbal."', monthly = '".$new_monthly."' WHERE id = '".$payment_id."'");
			
			if($update_q)
			{
				$insert_q = mysql_query("INSERT INTO adjustment SET customer_loanid = '".$loan_id."', customer_paymentid = '".$payment_id."', month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."'");
				if($insert_q)
				{
					$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
					if($update_lp)
					{
						$msg .= 'Loan period has been successfully updated.<br>';
					
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						
						if(strpos($get_l['loan_package'], 'SKIM F') === FALSE)
						{
							echo "<script>window.parent.location='payloan_a.php?id=$loan_id'</script>";
						}else
						{
							echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
						}
					}
				}
			}
		}
	}
}else
if(isset($_POST['adjust_flexiloan']))
{
	$loan_id = $_POST['loan_id'];
	$payment_id = $_POST['payment_id'];
	$month = $_POST['loan_period'];
	$add_on = $_POST['add_on'];
	$reduce = $_POST['reduce'];
	$reason = addslashes($_POST['reason']);
	
	$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$loan_id."'");
	$get_l = mysql_fetch_assoc($loan_q);
	
	$payment_q = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$payment_id."'");
	$get_p = mysql_fetch_assoc($payment_q);
		
	$adjustment_q = mysql_query("SELECT * FROM adjustment WHERE customer_loanid = '".$loan_id."' AND customer_paymentid = '".$payment_id."'");
	$get_a = mysql_fetch_assoc($adjustment_q);
	$ar = mysql_num_rows($adjustment_q);
	
	if($ar != 0)
	{
		if($get_l['loan_period'] != $month)
		{
			$update_l = mysql_query("UPDATE customer_loanpackage SET loan_period = '".$month."' WHERE id = '".$loan_id."'");
			
			if($update_l)
			{
				if($add_on != '' && $reduce == '')
				{
					$new_int = $get_p['int_balance'] + $add_on;
					$loan_total = $get_l['loan_total'] + $add_on;
				}else			
				if($reduce != '' && $add_on == '')
				{
					$new_int = $get_p['int_balance'] - $reduce;
					$loan_total = $get_l['loan_total'] - $reduce;
				}else
				{
					$new_int = $get_p['int_balance'];
					$loan_total = $get_l['loan_total'];
				}
					
				$update_q = mysql_query("UPDATE loan_payment_details SET int_balance = '".$new_int."' WHERE id = '".$payment_id."'");
				
				if($update_q)
				{
					$insert_q = mysql_query("UPDATE adjustment SET month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."' WHERE customer_loanid = '".$loan_id."' AND customer_paymentid = '".$payment_id."'");
					if($insert_q)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
						
						if($update_lp)
						{
							$msg .= 'Loan period has been successfully updated.<br>';
						
							echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
						}
					}
				}
			}
		}else
		{
			if($add_on != '' && $reduce == '')
			{
				$new_int = $get_p['int_balance'] + $add_on;
				$loan_total = $get_l['loan_total'] + $add_on;
			}else			
			if($reduce != '' && $add_on == '')
			{
				$new_int = $get_p['int_balance'] - $reduce;
				$loan_total = $get_l['loan_total'] - $reduce;
			}else
			{
				$new_int = $get_p['int_balance'];
				$loan_total = $get_l['loan_total'];
			}
			
			$update_q = mysql_query("UPDATE loan_payment_details SET int_balance = '".$new_int."' WHERE id = '".$payment_id."'");
			
			if($update_q)
			{
				$insert_q = mysql_query("UPDATE adjustment SET month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."' WHERE customer_loanid = '".$loan_id."' AND customer_paymentid = '".$payment_id."'");
				if($insert_q)
				{
					$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
					if($update_lp)
					{
						$msg .= 'Loan period has been successfully updated.<br>';
					
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						
						echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
					}
				}
			}
		}
	}else
	{
		if($get_l['loan_period'] != $month)
		{
			$update_l = mysql_query("UPDATE customer_loanpackage SET loan_period = '".$month."' WHERE id = '".$loan_id."'");
			
			if($update_l)
			{
				if($add_on != '' && $reduce == '')
				{
					$new_int = $get_p['int_balance'] + $add_on;
					$loan_total = $get_l['loan_total'] + $add_on;
				}else			
				if($reduce != '' && $add_on == '')
				{
					$new_int = $get_p['int_balance'] - $reduce;
					$loan_total = $get_l['loan_total'] - $reduce;
				}else
				{
					$new_int = $get_p['int_balance'];
					$loan_total = $get_l['loan_total'];
				}
				
				$update_q = mysql_query("UPDATE loan_payment_details SET int_balance = '".$new_int."' WHERE id = '".$payment_id."'");
				
				if($update_q)
				{
					$insert_q = mysql_query("INSERT INTO adjustment SET customer_loanid = '".$loan_id."', customer_paymentid = '".$payment_id."', month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."'");
					if($insert_q)
					{
						$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
						
						if($update_lp)
						{
							$msg .= 'Loan period has been successfully updated.<br>';
						
							echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
						}
					}
				}
			}
		}else
		{
			if($add_on != '' && $reduce == '')
			{
				$new_int = $get_p['int_balance'] + $add_on;
				$loan_total = $get_l['loan_total'] + $add_on;
			}else			
			if($reduce != '' && $add_on == '')
			{
				$new_int = $get_p['int_balance'] - $reduce;
				$loan_total = $get_l['loan_total'] - $reduce;
			}else
			{
				$new_int = $get_p['int_balance'];
				$loan_total = $get_l['loan_total'];
			}
				
			$update_q = mysql_query("UPDATE loan_payment_details SET int_balance = '".$new_int."' WHERE id = '".$payment_id."'");
			
			if($update_q)
			{
				$insert_q = mysql_query("INSERT INTO adjustment SET customer_loanid = '".$loan_id."', customer_paymentid = '".$payment_id."', month = '".$month."', add_on = '".$add_on."', reduce = '".$reduce."', reason = '".$reason."'");
				if($insert_q)
				{
					$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$loan_total."' WHERE id = '".$loan_id."'");
					if($update_lp)
					{
						$msg .= 'Loan period has been successfully updated.<br>';
					
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
						
						echo "<script>window.parent.location='payloan.php?id=$loan_id'</script>";
					}
				}
			}
		}
	}
}else
if($_POST['action'] == 'payloan_CEK')
{
	$amount = $_POST['amount'];
	$id = $_POST['id'];
	$date = $_POST['date'];
	$receipt = $_POST['receipt'];
	
	$sql = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$loan_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
	$get_l = mysql_fetch_assoc($loan_q);
	
	$update_q = mysql_query("UPDATE loan_payment_details SET payment_date = '".$date."', payment = '".$amount."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now() WHERE id = '".$id."'");
	
	if($update_q)
	{					
		$balance = $get_q['balance'] - $amount;
		//update balance 
		$update_bal_q = mysql_query("UPDATE loan_payment_details SET balance = '".$balance."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."' WHERE customer_loanid = '".$get_q['customer_loanid']."' AND id > '".$id."'");
		
		if($update_bal_q)
		{
			//if balance = 0
			if($balance == 0)
			{
				$nm = $get_q['month'] + 1;
				$insert_bal_q = mysql_query("INSERT INTO loan_payment_details SET balance = '".$balance."', customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."'");
				
				$updatelp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$get_q['customer_loanid']."'");
			}
		
			//insert into cashbook
			$insert_cb_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', transaction = 'REC', code = '".$receipt."', amount = '".$amount."', receipt_no = '".$receipt."', customer_id = '".$get_l['customer_id']."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
		
			if($insert_cb_q)
			{
				//insert balance1
				$insert_bal1_q = mysql_query("INSERT INTO balance_transaction SET received = '".$amount."', package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
				if($insert_bal1_q)
				{
					//insert balance2
					$insert_bal2_q = mysql_query("INSERT INTO balance_rec SET received = '".$amount."', package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
					
					$msg .= 'Payment has been successfully saved into record.<br>';
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				}
			}
		}	
	}
}/*else
if($_POST['action'] == 'payloan_CEKccm')
{
	$amount = $_POST['amount'];
	$date = $_POST['date'];
	$id = $_POST['id'];
	$ptype = $_POST['ptype'];
	
	$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql2_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND payment_date = '0000-00-00' ORDER BY month ASC");
	$get_q2 = mysql_fetch_assoc($sql2_q);
	
	if($ptype == 'CCM')
	{
		//insert payment
		$insert_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$id."', int_percent = '".$get_q2['int_percent']."', balance = '".$get_q2['balance']."', payment_date = '".$date."', payment = '".$amount."', package_id = '".$get_q2['package_id']."', receipt_no = '".$get_q2['receipt_no']."'");
	}else
	{//$ptype = INTEREST	
	
		//insert payment
		$insert_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$id."', int_percent = '".$get_q2['int_percent']."', balance = '".$get_q2['balance']."', payment_date = '".$date."', payment = '".$amount."', package_id = '".$get_q2['package_id']."', receipt_no = '".$get_q2['receipt_no']."'");
		
		if($insert_q)
		{
			//insert into cashbook
			$insert_cb_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q2['package_id']."', type = 'RECEIVED', transaction = 'INT', code = '".$get_q2['receipt_no']."', amount = '".$amount."', receipt_no = '".get_q2['receipt_no']."', customer_id = '".$get_q['customer_id']."', date = now()");
		
			if($insert_cb_q)
			{
				//insert balance1
				$insert_bal1_q = mysql_query("INSERT INTO balance_transaction SET received = '".$amount."', package_id = '".$get_q2['package_id']."', customer_loanid = '".$id."', date = now()");
				
				if($insert_bal1_q)
				{
					//insert balance2
					$insert_bal2_q = mysql_query("INSERT INTO balance_rec SET interest = '".$amount."', package_id = '".$get_2q['package_id']."', customer_loanid = '".$id."', bal_date = now()");
					
					$msg .= 'Payment has been successfully saved into record.<br>';
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				}
			}
		}	
	}
}*/
}
?>