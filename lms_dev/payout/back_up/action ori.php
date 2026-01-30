<?php
session_start();
include("../include/dbconnection.php");
include("../config.php");

mysql_query("SET TIME_ZONE = '+08:00'");

/*if($_POST['action'] == 'pay_customer')
{
	$id = $_POST['id'];
	
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Paid', payout_date = now() WHERE id = '".$id."'");
	
	if($update_q)
	{
		$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$id."'");
		$get_sql = mysql_fetch_assoc($sql);
		
		if($get_sql['loan_type'] == 'Fixed Loan')
		{
			$month = $get_sql['loan_period'];
			$monthly_amount = ($get_sql['loan_interesttotal']*1) / $month;
			
			$today = date('y-m-d');
			
			$time = strtotime($today);
			for($i=1; $i<=$month; $i++)
			{
				$final = date("Y-m-d", strtotime("+1 month", $time));
				
				$insert_q = mysql_query("INSERT INTO fixed_loan_details SET repayment_date = '".$final."', balance = '".$get_sql['loan_interesttotal']."', amount = '".$monthly_amount."', customer_loanid = '".$id."'");
				
				$time = strtotime($final);
			}
			
		}
		$msg .= 'Loan has been successfully paid to customer.<br>';
	
		$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
	}
	
}else*//*
if(isset($_POST['pay_cust']))
{
	$cid = $_POST['cid'];
	$lid = $_POST['lid'];
	
	$payout_date = $_POST['payout_date'];
	
	$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$lid."'");
	$get_sql = mysql_fetch_assoc($sql);
	
	$approved_amount = $get_sql['loan_amount'];
	$cash_pay = $_POST['cash_pay'];
	$cheque_pay = $_POST['cheque_pay'];
	$transfer_pay = $_POST['transfer_pay'];
	
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Paid', payout_date = '".$payout_date."' WHERE id = '".$lid."'");
	
	if($update_q)
	{
		$insert_p = mysql_query("INSERT INTO payout_details SET customer_id = '".$cid."', customer_loanid = '".$lid."', approved_amount = '".$approved_amount."', cash_pay = '".$cash_pay."', cheque_pay = '".$cheque_pay."', transfer_pay = '".$transfer_pay."'");
		
		if($insert_p)
		{			
			if($get_sql['loan_type'] == 'Fixed Loan')
			{
				$month = $get_sql['loan_period'];
				$monthly_amount1 = ($get_sql['loan_total']*1) / $month;
				$monthly_interest1 = ($get_sql['loan_interesttotal']*1) / $month;
				$monthly_loan1 = ($get_sql['loan_amount']*1) / $month;
				$monthly_amount = round($monthly_amount1);
				$monthly_interest = round($monthly_interest1);
				$monthly_loan = round($monthly_loan1);
				
				$insert_q = mysql_query("INSERT INTO fixed_loan_details SET customer_loanid = '".$lid."', month = '1', loan_balance = '".$get_sql['loan_total']."', loan_permonth = '".$monthly_loan."', payment_permonth = '".$monthly_amount."', interest_permonth = '".$monthly_interest."'");
									
			}
			
			if($get_sql['loan_type'] == 'Flexi Loan')
			{
				$insert_q = mysql_query("INSERT INTO flexi_loan_details SET customer_loanid = '".$lid."', month = '1', balance = '".$get_sql['loan_total']."'");
			}
			
			if($insert_q)
			{
			
				$msg .= 'Loan has been successfully paid to customer.<br>';
				$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
				echo "<script>window.parent.location='index.php'</script>";
			}
		}
		
	}
	
}*/

if(isset($_POST['pay_cust']))
{
	$cid = $_POST['cid'];
	$lid = $_POST['lid'];
	
	//only for flexi loan
	$month_receipt = $_POST['month_receipt'];
	
	$payout_date = date('Y-m-d', strtotime($_POST['payout_date']));
	
	$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$lid."'");
	$get_sql = mysql_fetch_assoc($sql);
	
	$approved_amount = $get_sql['loan_amount'];
	$cash_pay = $_POST['scash_pay'];
	$cheque_pay = $_POST['cheque_pay'];
	$transfer_pay = $_POST['transfer_pay'];
		
	$payment_date = date('Y-m-d', strtotime($_POST['payment_date']));
	$receipt_no = strtoupper($_POST['receipt_no']);
	
	//Mode of payment
	$modeofpay = $_POST['modeofpay'];
	$bank_t = $_POST['bank_t'];
	$b_acc_no = $_POST['b_acc_no'];
	$b_acc_holder = $_POST['b_acc_holder'];
	
	//8elem
	$stamping = $_POST['s'];
	$ctos = $_POST['ctos'];
	$ccris = $_POST['ccris'];
	$mlsettle = $_POST['ml'];
	$overlap = $_POST['ov'];
	$cm = $_POST['cm'];
	$others = $_POST['oth'];
	$nettp = $_POST['cash_pay'];

	
	//package
	$package1 = $get_sql['loan_package'];
	$package = mysql_real_escape_string($package1);
	
	$package_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".$package."'");
	$get_pack = mysql_fetch_assoc($package_q);
	
	$totalpayoutamount = $cash_pay + $cheque_pay + transfer_pay;
	
	//get_info from customer_loanpackage
	$cust_loanp_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$lid."'");
	$get_clp = mysql_fetch_assoc($cust_loanp_q);
	
	//for balance 2 rec
	$clpamt = $get_clp['loan_amount'];
	$commission = $clpamt - $totalpayoutamount;
	
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Paid', payout_date = '".$payout_date."', payout_id = '".$_SESSION['tap_loginid']."', payout_name = '".$_SESSION['login_name']."', pcreated_date = now() WHERE id = '".$lid."'");
	
	if($update_q)
	{
		if($get_sql['loan_type'] == 'Fixed Loan')
		{
			$insert_p = mysql_query("INSERT INTO payout_details SET customer_id = '".$cid."', customer_loanid = '".$lid."', approved_amount = '".$approved_amount."', cash_pay = '".$cash_pay."', cheque_pay = '".$cheque_pay."', transfer_pay = '".$transfer_pay."', package_id = '".$get_pack['id']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', bank_t = '".$bank_t."', b_acc_no = '".$b_acc_no."', b_acc_holder = '".$b_acc_holder."', mode_ofpay = '".$modeofpay."', stamping = '".$stamping."', ctos = '".$ctos."', ccris = '".$ccris."', mlsettle = '".$mlsettle."', overlap = '".$overlap."', cm = '".$cm."', others = '".$others."', nettp = '".$nettp."'");
						
			if($insert_p)
			{			
			
				$month = $get_sql['loan_period'];
				$monthly_interest1 = ($get_sql['loan_interesttotal']*1) / $month;
				$monthly_loan1 = ($get_sql['loan_amount']*1) / $month;
				$monthly_interest = ceil($monthly_interest1);
				$monthly_loan = ceil($monthly_loan1);
				
				$monthlytot1 = ($get_sql['loan_total'] *1 ) / $month;
				$monthlytot = ceil($monthlytot1);
				
				$balanceloan = $monthlytot * $month;
				
				$insert_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$lid."', month = '1', int_percent = '".$get_sql['loan_interest']."', monthly = '".$monthlytot."', balance = '".$balanceloan."', pokok = '".$monthly_loan."', interest = '".$monthly_interest."', next_paymentdate = '".$payment_date."', package_id = '".$get_pack['id']."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
				if($insert_q)
				{
					$cashbook_q = mysql_query("INSERT INTO cashbook SET type = 'PAY', package_id = '".$get_pack['id']."', table_id = '".$lid."', transaction = 'LOAN', code = '".$get_sql['loan_code']."', amount = '".$clpamt."', customer_id = '".$cid."', receipt_no = '".$receipt_no."', date = '".$payout_date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
					
					if($cashbook_q)
					{
						//insert cashbook commission
						$cashbook_q2 = mysql_query("INSERT INTO cashbook SET type = 'COMMISSION', package_id = '".$get_pack['id']."', table_id = '".$lid."', transaction = 'KOMISYEN', code = '".$get_sql['loan_code']."', amount = '".$commission."', customer_id = '".$cid."', receipt_no = '".$receipt_no."', date = '".$payout_date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
						
						//insert "LOAN"				
						$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_pack['id']."', loan = '".$clpamt."', customer_loanid = '".$lid."', bal_date = '".$payout_date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
						
						if($balance2_q)
						{
							//insert "COMMISSION"
							$balance2_q2 = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_pack['id']."', commission = '".$commission."', customer_loanid = '".$lid."', bal_date = '".$payout_date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
							
							if($balance2_q2)
							{
							
								//insert balance1
								$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_pack['id']."', customer_loanid = '".$lid."', loan = '".$balanceloan."', date = '".$payout_date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
								
								if($balance1_q)
								{
									$update_clp = mysql_query("UPDATE customer_loanpackage SET loan_total = '".$balanceloan."' WHERE id = '".$lid."'");
								
									$msg .= 'Loan has been successfully paid to customer.<br>';
									$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
									echo "<script>window.parent.location='index.php'</script>";
								}
							}
						}
					}
				}
			}
		}
		
		if($get_sql['loan_type'] == 'Flexi Loan')
		{
			$insert_p = mysql_query("INSERT INTO payout_details SET customer_id = '".$cid."', customer_loanid = '".$lid."', approved_amount = '".$approved_amount."', cash_pay = '".$cash_pay."', cheque_pay = '".$cheque_pay."', transfer_pay = '".$transfer_pay."', package_id = '".$get_pack['id']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', bank_t = '".$bank_t."', b_acc_no = '".$b_acc_no."', b_acc_holder = '".$b_acc_holder."', mode_ofpay = '".$modeofpay."', stamping = '".$stamping."', ctos = '".$ctos."', ccris = '".$ccris."', mlsettle = '".$mlsettle."', overlap = '".$overlap."', cm = '".$cm."', others = '".$others."', nettp = '".$nettp."'");
			
			$pay_id = mysql_insert_id();
			
			if($insert_p)
			{			
					$month = $get_sql['loan_period'];
					
					$insert_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$lid."', month = '1', int_percent = '".$get_sql['loan_interest']."', balance = '".$get_sql['loan_amount']."', int_balance = '".$get_sql['loan_interesttotal']."', next_paymentdate = '".$payment_date."', package_id = '".$get_pack['id']."', receipt_no = '".$receipt_no."', month_receipt = '".$month_receipt."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
				if($insert_q)
				{
					$cashbook_q = mysql_query("INSERT INTO cashbook SET type = 'PAY', package_id = '".$get_pack['id']."', table_id = '".$pay_id."', transaction = 'LOAN', customer_id = '".$cid."', code = '".$get_sql['loan_code']."', amount = '".$clpamt."', receipt_no = '".$receipt_no."', date = '".$payout_date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
					
					if($cashbook_q)
					{
						//insert cashbook commission
						$cashbook_q2 = mysql_query("INSERT INTO cashbook SET type = 'COMMISSION', package_id = '".$get_pack['id']."', table_id = '".$lid."', transaction = 'KOMISYEN', code = '".$get_sql['loan_code']."', amount = '".$commission."', receipt_no = '".$receipt_no."', date = '".$payout_date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
						
						//insert "LOAN"				
						$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_pack['id']."', loan = '".$clpamt."', customer_loanid = '".$lid."', month_receipt = '".$month_receipt."', bal_date = '".$payout_date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
						
						if($balance2_q)
						{
							if($get_pack['receipt_type'] == '1')
							{
								$mrpt = date('Y-m');
								//insert "COMMISSION"
								$balance2_q2 = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_pack['id']."', commission = '".$commission."', customer_loanid = '".$lid."', month_receipt = '".$mrpt."', bal_date = '".$payout_date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
							}
							
							//insert balance1
							$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_pack['id']."', customer_loanid = '".$lid."', loan = '".$clpamt."', date = '".$payout_date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
							
							//insert balance1 comm
							//$balance1_q2 = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_pack['id']."', customer_loanid = '".$lid."', received = '".$commission."', date = now()");
							
							$msg .= 'Loan has been successfully paid to customer.<br>';
							$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
							echo "<script>window.parent.location='index.php'</script>";
						}
					}
				}
			}
		}
	}
	
}else
if(isset($_POST['pay_custCek']))
{
	$cid = $_POST['cid'];
	$lid = $_POST['lid'];
		
	$payout_date = date('Y-m-d', strtotime($_POST['payout_date']));
	
	$sql = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$lid."'");
	$get_sql = mysql_fetch_assoc($sql);
	
	$approved_amount = $get_sql['loan_amount'];
	$cash_pay = $_POST['scash_pay'];
	$cheque_pay = $_POST['cheque_pay'];
	$transfer_pay = $_POST['transfer_pay'];
		
	$payment_date = date('Y-m-d', strtotime($_POST['payment_date']));
	$receipt_no = $_POST['receipt_no'];
	
	//Mode of payment
	$modeofpay = $_POST['modeofpay'];
	$bank_t = $_POST['bank_t'];
	$b_acc_no = $_POST['b_acc_no'];
	$b_acc_holder = $_POST['b_acc_holder'];
	
	//8elem
	$stamping = $_POST['s'];
	$ctos = $_POST['ctos'];
	$ccris = $_POST['ccris'];
	$mlsettle = $_POST['ml'];
	$overlap = $_POST['ov'];
	$cm = $_POST['cm'];
	$others = $_POST['oth'];
	$nettp = $_POST['cash_pay'];
	
	//package
	$package1 = $get_sql['loan_package'];
	$package = mysql_real_escape_string($package1);
	
	$package_q = mysql_query("SELECT * FROM loan_scheme WHERE scheme = '".$package."'");
	$get_pack = mysql_fetch_assoc($package_q);
	
	$totalpayoutamount = $cash_pay + $cheque_pay + transfer_pay;
	
	//get_info from customer_loanpackage
	$cust_loanp_q = mysql_query("SELECT * FROM customer_loanpackage WHERE id = '".$lid."'");
	$get_clp = mysql_fetch_assoc($cust_loanp_q);
	
	//for balance 2 rec
	$clpamt = $get_clp['loan_amount'];
	$commission = $clpamt - $totalpayoutamount;
	
	$update_q = mysql_query("UPDATE customer_loanpackage SET loan_status = 'Paid', payout_date = '".$payout_date."', payout_id = '".$_SESSION['tap_loginid']."', payout_name = '".$_SESSION['login_name']."', pcreated_date = now() WHERE id = '".$lid."'");
	
	if($update_q)
	{
		$insert_p = mysql_query("INSERT INTO payout_details SET customer_id = '".$cid."', customer_loanid = '".$lid."', approved_amount = '".$approved_amount."', cash_pay = '".$cash_pay."', cheque_pay = '".$cheque_pay."', transfer_pay = '".$transfer_pay."', package_id = '".$get_pack['id']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', bank_t = '".$bank_t."', b_acc_no = '".$b_acc_no."', b_acc_holder = '".$b_acc_holder."', mode_ofpay = '".$modeofpay."', stamping = '".$stamping."', ctos = '".$ctos."', ccris = '".$ccris."', mlsettle = '".$mlsettle."', overlap = '".$overlap."', cm = '".$cm."', others = '".$others."', nettp = '".$nettp."'");
		
		$pay_id = mysql_insert_id();
		
		if($insert_p)
		{			
			$month = $get_sql['loan_period'];
			$inttotal = $get_sql['loan_interesttotal'];
			
			$cekbalance = $get_sql['loan_total'];
			
			$monthint = $inttotal / $month;
			$monthpayment = $get_sql['loan_amount'] / $month;
			//1st month
			$firstmonth = $monthpayment + $monthint + $monthint;
			
			//nextmonth
			$nextmonth = $monthpayment + $monthint;				
			
			//lastmonth
			$lastmonth = $monthpayment;
			
			if($month == 1)
			{
				$insert_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$lid."', month = '".$month."', int_percent = '".$get_sql['loan_interest']."', monthly = '".$cekbalance."', balance = '".$cekbalance."', pokok = '".$cekbalance."', interest = '".$inttotal."', next_paymentdate = '".$payment_date."', package_id = '".$get_pack['id']."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
			}
			else
			{
				for($i = 1; $i <= $month; $i++)
				{
					$npd = date('d', strtotime($payment_date));
					$npm = date('m', strtotime($payment_date));
					$npy = date('Y', strtotime($payment_date));
					
					if($i == 1)
					{
						$int_bulanan = $monthint*2;
						$insert_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$lid."', month = '".$i."', int_percent = '".$get_sql['loan_interest']."', monthly = '".$firstmonth."', balance = '".$cekbalance."', pokok = '".$monthpayment."', interest = '".$int_bulanan."', next_paymentdate = '".$payment_date."', package_id = '".$get_pack['id']."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					}else
					if($i == $month)
					{
						$npm = $npm+$i-1;
						if($npm >12)
						{
							$npm = $npm - 12;
							$npy = $npy + 1;
						}
						$pdate = $npy.'-'.$npm.'-'.$npd;
						
						$insert_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$lid."', month = '".$i."', int_percent = '".$get_sql['loan_interest']."', monthly = '".$lastmonth."', balance = '".$cekbalance."', pokok = '".$monthpayment."', next_paymentdate = '".$pdate."', package_id = '".$get_pack['id']."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					}else
					{
						$npm = $npm+$i-1;
						if($npm >12)
						{
							$npm = $npm - 12;
							$npy = $npy + 1;
						}
						$pdate = $npy.'-'.$npm.'-'.$npd;
						
						$insert_q = mysql_query("INSERT INTO loan_payment_details SET customer_loanid = '".$lid."', month = '".$i."', int_percent = '".$get_sql['loan_interest']."', monthly = '".$nextmonth."', balance = '".$cekbalance."', pokok = '".$monthpayment."', interest = '".$monthint."', next_paymentdate = '".$pdate."', package_id = '".$get_pack['id']."', receipt_no = '".$receipt_no."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					}
				}
			}

			$cashbook_q = mysql_query("INSERT INTO cashbook SET type = 'PAY', package_id = '".$get_pack['id']."', table_id = '".$pay_id."', transaction = 'LOAN', customer_id = '".$cid."', code = '".$get_sql['loan_code']."', amount = '".$clpamt."', receipt_no = '".$receipt_no."', date = '".$payout_date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
			
			if($cashbook_q)
			{
				//insert cashbook commission
				$cashbook_q2 = mysql_query("INSERT INTO cashbook SET type = 'COMMISSION', package_id = '".$get_pack['id']."', table_id = '".$lid."', transaction = 'KOMISYEN', code = '".$get_sql['loan_code']."', amount = '".$commission."', receipt_no = '".$receipt_no."', date = '".$payout_date."', staff_id = '".$_SESSION['taplogin_id']."', staff_name = '".$_SESSION['login_name']."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."', created_date = now()");
				
				//insert "LOAN"				
				$balance2_q = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_pack['id']."', loan = '".$clpamt."', customer_loanid = '".$lid."', bal_date = '".$payout_date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
				
				if($balance2_q)
				{
					//insert "COMMISSION"
					$balance2_q2 = mysql_query("INSERT INTO balance_rec SET package_id = '".$get_pack['id']."', commission = '".$commission."', customer_loanid = '".$lid."', bal_date = '".$payout_date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
					//insert balance1
					$balance1_q = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_pack['id']."', customer_loanid = '".$lid."', loan = '".$clpamt."', date = '".$payout_date."', branch_id = '".$_SESSION['login_branchid']."', branch_name = '".$_SESSION['login_branch']."'");
					
					//insert balance1 comm
					//$balance1_q2 = mysql_query("INSERT INTO balance_transaction SET package_id = '".$get_pack['id']."', customer_loanid = '".$lid."', received = '".$commission."', date = now()");
					
					$msg .= 'Loan has been successfully paid to customer.<br>';
					$_SESSION['msg'] = "<div class='success'>".$msg."</div>";
					echo "<script>window.parent.location='index.php'</script>";
				}
			}
			
		}
	}	
}
?>