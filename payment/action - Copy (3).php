<?php
session_start();
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
			$cashbook_q = mysql_query("INSERT INTO cashbook SET type = 'RECIEVED', package_id = '".$get_q['package_id']."', table_id = '".$id."', transaction = 'REC', code = '".$get_lp['loan_code']."', receipt_no = '".$get_q['receipt_no']."', amount = '".$amount."', date = now()");
			
			$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', bal_date = now()");
		}
		
		if($int_amount > '0')
		{
			$cashbook_q = mysql_query("INSERT INTO cashbook SET type = 'RECIEVED', package_id = '".$get_q['package_id']."', table_id = '".$id."', transaction = 'REC', code = '".$get_lp['loan_code']."', receipt_no = '".$get_q['receipt_no']."', amount = '".$int_amount."', date = now()");
			
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
if($_POST['action'] == 'payloanf')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$int_amount = $_POST['intamount'];
	$date = $_POST['date'];
	$period = $_POST['period'];
	$month = $_POST['month'];
	$next_paymentdate = $_POST['nextdate'];
	$receipt_no = $_POST['receipt']; 
	$month_receipt = $_POST['monthreceipt'];
	$new_loan = $_POST['newloan'];
	
	$sql = mysql_query("SELECT * FROM loan_payment_details WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql_2 = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$get_q['customer_loanid']."'");
	$get_lp = mysql_fetch_assoc($sql_2);
	
	$loancode = $get_lp['loan_code'];
	
	$updatecustlp_q = mysql_query("UPDATE customer_loanpackage SET loan_code = '".$receipt_no."' WHERE id = '".$get_q['customer_loanid']."'");
	
	$update_q = mysql_query("UPDATE loan_payment_details SET payment = '".$amount."', payment_int = '".$int_amount."', payment_date = '".$date."' WHERE id = '".$id."'");
	
	//$new_loan = 0
	if($new_loan == '')
	{
		//loan balance
		$loan_balance = $get_q['balance'] - $amount;

		//insert new row if $loan_balance != 0
		if($loan_balance > 0)
		{
			//if only pay for interest (tukar resit)
			if($amount == 0)
			{
			
				if($get_q['month_receipt'] != $month_receipt)
				{			
					//insert to cashbook (transaction shows that settle the loan but actually only pay for the interest (tukar resit)
					$cashbook_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', amount = '".$loan_balance."', transaction = 'REC', code = '".$loancode."', receipt_no = '".$get_q['receipt_no']."', customer_id = '".$get_lp['customer_id']."', table_id = '".$id."', date = now()");
					
					//insert to cashbook pay the loan (new receipt no)
					$cashbook2_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'PAY', amount = '".$loan_balance."', transaction = 'LOAN', code = '".$loancode."', receipt_no = '".$receipt_no."', customer_id = '".$get_lp['customer_id']."', date = now()");
					
					//insert into balance1 (transaction shows that settle the loan but actually only pay for the interest (tukar resit)
				$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$loan_balance."', date = now()");
				
					//insert to balance1 pay the loan (new receipt no)
					$balance1_ql = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$loan_balance."', date = now()");
					
					//insert into balance2 (transaction shows that settle the loan but actually only pay for the interest (tukar resit)
					$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$loan_balance."', month_receipt = '".$get_q['month_receipt']."', bal_date = now()");
					
					//insert to balance2 pay the loan (new receipt no)
					$balance2_ql = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$loan_balance."', month_receipt = '".$month_receipt."', bal_date = now()");
				}
				
				//insert to cashbook record the interest
				$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$int_amount."', transaction = 'KOMISYEN', receipt_no = '".$receipt_no."', date = now()");
							
				
				//insert new loan_payment_details
				$nm = $get_q['month'] + 1;
				$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '".$loan_balance."',next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."'");
				
				$msg .= 'Payment has been successfully saved into record.<br>';
				$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				
			}else
			{
				//insert to cashbook rec the actual amount paid
				$cashbook_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', amount = '".$amount."', transaction = 'CCM', code = '".$loancode."', receipt_no = '".$get_q['receipt_no']."', customer_id = '".$get_lp['customer_id']."', table_id = '".$id."', date = now()");
				
				if($int_amount != 0)
				{				
					//insert to cashbook record the interest
					$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$int_amount."', transaction = 'KOMISYEN', receipt_no = '".$receipt_no."', date = now()");
				}
							
				//insert into balance1 rec the actual amount paid
				$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = now()");
			
				//insert into balance2 rec the actual amount paid
				$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', month_receipt = '".$get_q['month_receipt']."', bal_date = now()");
				
				//insert new loan_payment_details
				$nm = $get_q['month'] + 1;
				
				$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '".$loan_balance."',next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."'");
				
				$msg .= 'Payment has been successfully saved into record.<br>';
				$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				
			}
		}else
		{
			//insert to cashbook rec the actual amount paid
			$cashbook_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', amount = '".$amount."', transaction = 'CCM', code = '".$loancode."', receipt_no = '".$get_q['receipt_no']."', customer_id = '".$get_lp['customer_id']."', table_id = '".$id."', date = now()");
			
			if($int_amount != 0)
			{				
				//insert to cashbook record the interest
				$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$int_amount."', transaction = 'KOMISYEN', receipt_no = '".$receipt_no."', date = now()");
			}
						
			//insert into balance1 rec the actual amount paid
			$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = now()");
		
			//insert into balance2 rec the actual amount paid
			$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', month_receipt = '".$get_q['month_receipt']."', bal_date = now()");
			
			//insert new loan_payment_details
			$nm = $get_q['month'] + 1;
			
			$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '0', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."'");
			
			//set loan = FINISHED
			$update_lp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$get_q['customer_loanid']."'");
			
			$msg .= 'Payment has been successfully saved into record.<br>';
			$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
		}
	}else //$new_loan != 0
	{	
		//calculate percent	
		$fmloan_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$get_q['customer_loanid']."' AND month = '1'");
		$get_fml = mysql_fetch_assoc($fmloan_q);
		$fmint = $get_fml['int_balance'];
		$fmloan = $get_fml['balance'];
		$percentint = $fmint / $fmloan;
		
		$newinterest = $percentint * $new_loan;
		
		//insert to cashbook (transaction shows that settle the loan)
		$cashbook_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'RECEIVED', amount = '".$amount."', transaction = 'REC', code = '".$loancode."', receipt_no = '".$get_q['receipt_no']."', customer_id = '".$get_lp['customer_id']."', table_id = '".$id."', date = now()");
		
		//insert to cashbook (add new loan to record)
		$cashbook2_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'PAY', amount = '".$new_loan."', transaction = 'LOAN', code = '".$loancode."', receipt_no = '".$receipt_no."', customer_id = '".$get_lp['customer_id']."', date = now()");
		
		//insert into cashbook interest transaction
		$cashbook3_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q['package_id']."', type = 'COMMISSION', amount = '".$newinterest."', transaction = 'KOMISYEN', receipt_no = '".$receipt_no."', date = now()");
		
		//insert into balance1 new loan amount
		$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$new_loan."', date = now()");
		
		//insert into balance1 rec the actual amount paid
		$balance1_qr = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = now()");
		
		//insert into balance2 rec the actual amount paid
		$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', month_receipt = '".$get_q['month_receipt']."', bal_date = now()");
		
		//insert into balance2 rec the new loan amount
		$balance2_qr = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', loan = '".$new_loan."', month_receipt = '".$month_receipt."', bal_date = now()");
		
		//insert new loan_payment_details
		$nm = $get_q['month'] + 1;
		$insert_lp_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$nm."', int_percent = '".$get_q['int_percent']."', balance = '".$new_loan."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."'");
		
		$msg .= 'Payment has been successfully saved into record.<br>';
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}	
}else
if($_POST['action'] == 'payloan')
{
	$id = $_POST['id'];
	$amount = $_POST['amount'];
	$date = $_POST['date'];
	$period = $_POST['period'];
	$month = $_POST['month'];
	$next_paymentdate = $_POST['nextdate'];
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
	
	$adjustment_q = mysql_query("SELECT * FROM adjustment WHERE customer_loanid = '".$get_q['customer_loanid']."' AND customer_paymentid = '".$id."'");
	$get_a = mysql_fetch_assoc($adjustment_q);
	$ar = mysql_num_rows($adjustment_q);
	
	$actamt = $amount - $min_int;
		
	if($ar == 0)
	{
		$update_q = mysql_query("UPDATE loan_payment_details SET payment = '".$amount."', payment_date = '".$date."' WHERE id = '".$id."'");
	
		if($update_q)
		{
			$cashbook_q = mysql_query("INSERT INTO cashbook SET type = 'RECIEVED', package_id = '".$get_q['package_id']."', table_id = '".$id."', transaction = 'REC', code = '".$get_lp['loan_code']."', receipt_no = '".$get_q['receipt_no']."', amount = '".$amount."', date = now()");
			
			if($num_select == '1')
			{
				$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', bal_date = now()");
				$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = now()");
			}
			else
			{
				$actamt = $amount - $min_int;
				$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$actamt."', bal_date = '".$get_select['next_paymentdate']."'");
				$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$actamt."', date = now()");
			}
			
			$newmonth = $month + 1;
			
			if($newmonth <= $period)
			{
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
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '".$newbalance."', monthly = '".$monthly."', pokok = '".$pokok."', interest = '".$get_q['interest']."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."'");
					
					if($insert_new)
					{
				
						$msg .= 'Payment has been successfully saved into record.<br>';
			
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
					}
				}
				else
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', balance = '0'");
					
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
	}else
	{
		$update_q = mysql_query("UPDATE loan_payment_details SET payment = '".$amount."', payment_date = '".$date."' WHERE id = '".$id."'");
		
		if($update_q)
		{
			$cashbook_q = mysql_query("INSERT INTO cashbook SET type = 'RECIEVED', package_id = '".$get_q['package_id']."', table_id = '".$id."', transaction = 'REC', code = '".$get_lp['loan_code']."', amount = '".$amount."', receipt_no = '".$get_q['receipt_no']."', date = now()");
			
			if($num_select == '1')
			{
				$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', bal_date = now()");
				$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$amount."', date = now()");
			}
			else
			{
				$actamt = $amount - $min_int;
				$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$actamt."', bal_date = '".$get_select['next_paymentdate']."'");
				$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_q['package_id']."', customer_loanid = '".$get_q['customer_loanid']."', received = '".$actamt."', date = now()");
			}
			
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
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', int_percent = '".$get_q['int_percent']."', balance = '".$newbalance."', monthly = '".$monthly."', pokok = '".$pokok."', interest = '".$intmonthly."', next_paymentdate = '".$next_paymentdate."', package_id = '".$get_q['package_id']."', receipt_no = '".$receipt_no."'");
					
					if($insert_new)
					{
				
						$msg .= 'Payment has been successfully saved into record.<br>';
			
						$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
					}
				}
				else
				{
					$insert_new = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$get_q['customer_loanid']."', month = '".$newmonth."', balance = '0'");
					
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
}

?>