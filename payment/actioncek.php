<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");


if($_POST['action'] == 'payloan_CEKccm')
{
	$amount = $_POST['amount'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	$id = $_POST['id'];
	$ptype = $_POST['ptype'];
	
	$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql2_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND payment_date = '0000-00-00' ORDER BY month ASC");
	$get_q2 = mysql_fetch_assoc($sql2_q);
	
	$bal_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND balance != 0 ORDER BY balance ASC");
	$get_b = mysql_fetch_assoc($bal_q);
	
	$month_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' ORDER BY month DESC");
	$newmonth_q = mysql_fetch_assoc($month_q);
	
	$newmonth = $newmonth_q['month'] + 1;
	if($ptype == 'CCM')
	{
		$balance_new = $get_b['balance'] - $amount;
		//$update_bal = mysql_query("UPDATE loan_payment_details SET balance = '".$balance_new."' WHERE payment_date != '0000-00-00' AND customer_loanid = '".$id."'");		
	
		//insert payment
		$insert_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$id."', int_percent = '".$get_q2['int_percent']."', balance = '".$balance_new."', payment_date = '".$date."', month = '".$newmonth."', payment = '".$amount."', package_id = '".$get_q2['package_id']."', receipt_no = '".$get_q2['receipt_no']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
		
		if($insert_q)
		{
			//insert into cashbook
			$insert_cb_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q2['package_id']."', type = 'RECEIVED', transaction = 'CCM', code = '".$get_q2['receipt_no']."', amount = '".$amount."', receipt_no = '".$get_q2['receipt_no']."', customer_id = '".$get_q['customer_id']."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
		
			if($insert_cb_q)
			{
				//insert balance1
				$insert_bal1_q = mysql_query("INSERT INTO balance_transaction SET received = '".$amount."', package_id = '".$get_q2['package_id']."', customer_loanid = '".$id."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
				if($insert_bal1_q)
				{
					
					//insert balance2
					$insert_bal2_q = mysql_query("INSERT INTO balance_rec SET received = '".$amount."', package_id = '".$get_q2['package_id']."', customer_loanid = '".$id."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
					if($balance_new == 0)
					{
						$updatelp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$id."'");
					}
					$msg .= 'Payment has been successfully saved into record.<br>';
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				}
			}
		}
	}else
	{//$ptype = INTEREST	
	
		$bal_q = mysql_query("SELECT * FROM loan_payment_details WHERE customer_loanid = '".$id."' AND balance != 0 ORDER BY balance ASC");
		$get_b = mysql_fetch_assoc($bal_q);
		//insert payment
		$insert_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$id."', int_percent = '".$get_q2['int_percent']."', balance = '".$get_b['balance']."', payment_date = '".$date."', month = '".$newmonth."', payment = '".$amount."', package_id = '".$get_q2['package_id']."', receipt_no = '".$get_q2['receipt_no']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
		
		if($insert_q)
		{
			//insert into cashbook
			$insert_cb_q = mysql_query("INSERT INTO cashbook SET package_id = '".$get_q2['package_id']."', type = 'RECEIVED', transaction = 'INT', code = '".$get_q2['receipt_no']."', amount = '".$amount."', receipt_no = '".$get_q2['receipt_no']."', customer_id = '".$get_q['customer_id']."', date = '".$date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
		
			if($insert_cb_q)
			{
				//insert balance1
				$insert_bal1_q = mysql_query("INSERT INTO balance_transaction SET received = '".$amount."', package_id = '".$get_q2['package_id']."', customer_loanid = '".$id."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
				if($insert_bal1_q)
				{
				
					//INSERT LOAN BALANCE1
					$insert_bal1_loan = mysql_query("INSERT INTO balance_transaction SET loan = '".$amount."', package_id = '".$get_q2['package_id']."', customer_loanid = '".$id."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
					//insert balance2
					$insert_bal2_q = mysql_query("INSERT INTO balance_rec SET interest = '".$amount."', package_id = '".$get_q2['package_id']."', customer_loanid = '".$id."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
					$msg .= 'Payment has been successfully saved into record.<br>';
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				}
			}
		}
	}
}else
if($_POST['action'] == 'payloan_CEKccm2') //loan period = 1
{
	$amount = $_POST['amount'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	
	//where does this date2 came from
	$date2 = date('Y-m-d', strtotime($_POST['date2']));
	
	$id = $_POST['id'];
	$ptype = $_POST['ptype'];
	
	$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
	$get_q = mysql_fetch_assoc($sql);
	
	$sql2_q = mysql_query("SELECT * FROM loan_payment_details WHERE 
							customer_loanid = '".$id."' AND 
							payment_date = '0000-00-00' ORDER BY 
							month ASC");
	$get_q2 = mysql_fetch_assoc($sql2_q);
	
	$bal_q = mysql_query("SELECT * FROM loan_payment_details WHERE 
							customer_loanid = '".$id."' AND 
							balance != 0 ORDER BY 
							balance ASC");
	$get_b = mysql_fetch_assoc($bal_q);
	
	$month_q = mysql_query("SELECT * FROM loan_payment_details WHERE 
								customer_loanid = '".$id."' ORDER BY 
								month DESC");
	$newmonth_q = mysql_fetch_assoc($month_q);
	
	//full interest
	$fullint = $get_q['loan_total'] * ($get_q2['int_percent'] / 100);
	
	//update payment
	$update_p = mysql_query("UPDATE loan_payment_details SET 
								payment_date = '".$date."' WHERE 
								id = '".$get_q2['id']."'");
	
	$newmonth = $newmonth_q['month'] + 1;
	if($ptype == 'CCM')
	{
		$balance_new = $get_b['balance'] - $amount;
		//update payment
		$update_p = mysql_query("UPDATE loan_payment_details SET 
									payment_date = '".$date."', 
									payment = '".$amount."' WHERE 
									id = '".$get_q2['id']."'");
		
		$bal_q = mysql_query("SELECT * FROM loan_payment_details WHERE 
								customer_loanid = '".$id."' AND 
								balance != 0 ORDER BY 
								balance ASC, month DESC");
		$get_b = mysql_fetch_assoc($bal_q);
		
		$date_q = mysql_query("SELECT payment_date FROM 
								loan_payment_details WHERE 
								customer_loanid = '".$id."' AND 
								next_paymentdate = '".$date2."'");//*******
		$get_date = mysql_num_rows($date_q);
		
		//next payment date
		if($get_date > 1)
		{
			$date1 = $get_b['next_paymentdate'];
			$newdate = strtotime ( 'last day of +1 month' , strtotime ( $date1 ) ) ;
			$newdate = date ( 'Y-m-d' , $newdate );
		}else
		{
			//get before int amount
			$bint_q = mysql_query("SELECT SUM(payment_int) AS paymentint FROM 
									loan_payment_details WHERE 
									customer_loanid = '".$id."' AND 
									next_paymentdate = '".$get_b['next_paymentdate']."'");
			$bint = mysql_fetch_assoc($bint_q);
			$namount = $amount + $bint['paymentint'];
			
			
			if($namount >= $fullint)
			{
				$date1 = $get_b['next_paymentdate'];
				$newdate = strtotime ( 'last day of +1 month' , strtotime ( $date1 ) );
				$newdate = date ( 'Y-m-d' , $newdate );
			}else
			{
				$newdate = $get_b['next_paymentdate'];
			}
		}
		
		//insert payment
		$insert_q = mysql_query("INSERT INTO loan_payment_details SET 
									customer_loanid = '".$id."', 
									int_percent = '".$get_q2['int_percent']."', 
									balance = '".$balance_new."', 
									month = '".$newmonth."', 
									monthly = '".$balance_new."', 
									package_id = '".$get_q2['package_id']."', 
									receipt_no = '".$get_q2['receipt_no']."', 
									next_paymentdate = '".$newdate."', 
									branch_id = '".$_SESSION['login_branchid']."', 
									branch_name = '".$_SESSION['login_branch']."'");
		
		if($balance_new == 0)
		{
			$updatelp = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Finished' WHERE id = '".$id."'");
		}
		
		if($insert_q)
		{
			//insert into cashbook
			$insert_cb_q = mysql_query("INSERT INTO cashbook SET 
											package_id = '".$get_q2['package_id']."', 
											type = 'RECEIVED', 
											transaction = 'CCM', 
											code = '".$get_q2['receipt_no']."', 
											amount = '".$amount."', 
											receipt_no = '".$get_q2['receipt_no']."', 
											customer_id = '".$get_q['customer_id']."', 
											date = '".$date."', 
											staff_id = '".$_SESSION['taplogin_id']."', 
											staff_name = '".$_SESSION['login_name']."', 
											branch_id = '".$_SESSION['login_branchid']."', 
											branch_name = '".$_SESSION['login_branch']."', 
											created_date = now()");
			
			if($insert_cb_q)
			{
				//insert balance1
				$insert_bal1_q = mysql_query("INSERT INTO balance_transaction SET 
												received = '".$amount."', 
												package_id = '".$get_q2['package_id']."', 
												customer_loanid = '".$id."', 
												date = '".$date."', 
												branch_id = '".$_SESSION['login_branchid']."', 
												branch_name = '".$_SESSION['login_branch']."'");
				
				if($insert_bal1_q)
				{
					
					//insert balance2
					$insert_bal2_q = mysql_query("INSERT INTO balance_rec SET 
													received = '".$amount."', 
													package_id = '".$get_q2['package_id']."', 
													customer_loanid = '".$id."', 
													bal_date = '".$date."', 
													branch_id = '".$_SESSION['login_branchid']."', 
													branch_name = '".$_SESSION['login_branch']."'");
					
					$msg .= 'Payment has been successfully saved into record.<br>';
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				}
			}
		}
	}else
	{//$ptype = INTEREST	
	
		
		//update payment
		$update_p = mysql_query("UPDATE loan_payment_details SET 
									payment_date = '".$date."', 
									payment = '".$amount."', 
									payment_int = '".$amount."' WHERE 
									id = '".$get_q2['id']."'");
		
		$bal_q = mysql_query("SELECT * FROM loan_payment_details WHERE 
								customer_loanid = '".$id."' AND 
								balance != 0 ORDER BY 
								balance ASC, month DESC");
		$get_b = mysql_fetch_assoc($bal_q);
		
		$date_q = mysql_query("SELECT payment_date FROM 
								loan_payment_details WHERE 
								customer_loanid = '".$id."' AND 
								next_paymentdate = '".$date2."'");
		$get_date = mysql_num_rows($date_q);
		
		//next payment date
		if($get_date > 1)
		{
			$date1 = $get_b['next_paymentdate'];
			$newdate = strtotime ( '+1 month' , strtotime ( $date1 ) ) ;
			$newdate = date ( 'Y-m-d' , $newdate );
		}else
		{
			//get before int amount
			$bint_q = mysql_query("SELECT SUM(payment_int) AS paymentint FROM 
									loan_payment_details WHERE 
									customer_loanid = '".$id."' AND 
									next_paymentdate = '".$get_b['next_paymentdate']."'");
			$bint = mysql_fetch_assoc($bint_q);
			$namount = $amount + $bint['paymentint'];
			
			
			if($namount >= $fullint)
			{
				$date1 = $get_b['next_paymentdate'];
				$newdate = strtotime ( '+1 month' , strtotime ( $date1 ) ) ;
				$newdate = date ( 'Y-m-d' , $newdate );
			}else
			{
				$newdate = $get_b['next_paymentdate'];
			}
		}
		
		//insert payment
		$insert_q = mysql_query("INSERT INTO loan_payment_details SET 
									customer_loanid = '".$id."', 
									int_percent = '".$get_q2['int_percent']."', 
									balance = '".$get_b['balance']."', 
									month = '".$newmonth."', 
									monthly = '".$get_b['balance']."', 
									package_id = '".$get_q2['package_id']."', 
									next_paymentdate = '".$newdate."', 
									receipt_no = '".$get_q2['receipt_no']."', 
									branch_id = '".$_SESSION['login_branchid']."', 
									branch_name = '".$_SESSION['login_branch']."'");
		
		if($insert_q)
		{
			//insert into cashbook
			$insert_cb_q = mysql_query("INSERT INTO cashbook SET 
											package_id = '".$get_q2['package_id']."', 
											type = 'RECEIVED', 
											transaction = 'INT', 
											code = '".$get_q2['receipt_no']."', 
											amount = '".$amount."', 
											receipt_no = '".$get_q2['receipt_no']."', 
											customer_id = '".$get_q['customer_id']."', 
											date = '".$date."', 
											staff_id = '".$_SESSION['taplogin_id']."', 
											staff_name = '".$_SESSION['login_name']."', 
											branch_id = '".$_SESSION['login_branchid']."', 
											branch_name = '".$_SESSION['login_branch']."', 
											created_date = now()");
		
			if($insert_cb_q)
			{
				//insert balance1
				$insert_bal1_q = mysql_query("INSERT INTO balance_transaction SET 
												received = '".$amount."', 
												package_id = '".$get_q2['package_id']."', 
												customer_loanid = '".$id."', 
												date = '".$date."', 
												branch_id = '".$_SESSION['login_branchid']."', 
												branch_name = '".$_SESSION['login_branch']."'");
				
				if($insert_bal1_q)
				{
				
					//INSERT LOAN BALANCE1
					$insert_bal1_loan = mysql_query("INSERT INTO balance_transaction SET loan = '".$amount."', package_id = '".$get_q2['package_id']."', customer_loanid = '".$id."', date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
					//insert balance2
					$insert_bal2_q = mysql_query("INSERT INTO balance_rec SET interest = '".$amount."', package_id = '".$get_q2['package_id']."', customer_loanid = '".$id."', bal_date = '".$date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
					
					$msg .= 'Payment has been successfully saved into record.<br>';
					
				
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				}
			}
		}
	}
}

?>